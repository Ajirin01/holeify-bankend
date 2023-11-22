<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Worker;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\DoneTask;
use DB;

use App\Jobs\ProcessWorkerTransfer;
use Illuminate\Support\Facades\Queue;

// use Paystack;
use \Yabacon\Paystack;
use \Flutterwave;

class WithdrawalController extends Controller
{
    private $flutterwave;

    public function check_account_detail_exist(Request $request){
        $user_id = $request->worker_id;

        $user = User::find($user_id); 

        $totalUnpaidEarnings = $user->worker->done_tasks->where('paid', false)->sum('earning');

        if ($totalUnpaidEarnings >= 250 && !$user->worker->account_number) {
            // return response()->json($user->worker->done_tasks->where('paid', false)->sum('earning'));
            return response()->json(true);
        }
        return response()->json(false);
    }

    public function resolve_account(Request $request){
        // Replace 'YOUR_API_KEY' with your actual Flutterwave API key
        $api_key = 'FLWSECK-7588ff752c1a30cf368e1bfc2d19d227-18b5f8767bfvt-X';

        // The endpoint for account verification
        $endpoint = 'https://api.flutterwave.com/v3/accounts/resolve';

        // Replace 'ACCOUNT_NUMBER' and 'BANK_CODE' with the actual account details you want to verify
        $account_number = $request->account_number;
        $bank_code = $request->bank_code;

        // Prepare data for the request
        $data = [
            'account_number' => $account_number,
            'account_bank' => $bank_code,
        ];

        // Convert data to JSON format
        $data_json = json_encode($data);

        // Set up cURL options
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !config('app.debug'));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return response()->json(['Error'=> curl_error($ch)]);
        }

        // Close cURL session
        curl_close($ch);

        // Display the API response
        return response()->json($response);
    }

    public function __construct(Flutterwave $flutterwave)
    {
        $this->flutterwave = $flutterwave;
    }

    public function save_account_detail(Request $request){
        // return response()->json($request->all());
        return response()->json(['message'=> 'success', Worker::find($request->id)->update($request->all())], 200);
    }

    public function create_withdrawal_details(){
        $workers_to_pay = $this->getWorkersToPay();

        foreach ($workers_to_pay as $key => $worker) {
            // Creating Withdrawal Records
            $withdrawal = new Withdrawal([
                'worker_id' => $worker->id,
                'amount' => $worker->total_earning, // Replace with the calculated total earnings
                'done_tasks' => json_encode($worker->done_tasks),
                'status' => 'pending',
            ]);

            $worker_withdrawal = Withdrawal::where("worker_id", $worker->id)->first();

            if ($worker_withdrawal == null || $worker_withdrawal->created_at < now()->subDays(25)) {
                $withdrawal->save();
            }
        }

        // return response()->json(Withdrawal::where('status', 'pending')->get());
        return response()->json($this->get_withdrawal_details());

    }

    public function get_withdrawal_details(){
        return Withdrawal::with("worker.user")->get();
    }

    public function withdrawal(Request $request)
    {
        // Bulk Transfer Logic
        // return response()->json($request->all());
        $withdrawals = Withdrawal::whereIn('id', $request->IDs)->get();
        // return json_encode($withdrawals);
        foreach ($withdrawals as $withdrawal) {
            $worker = $withdrawal->worker;
            // Update withdrawal status to 'completed'
            $withdrawal->update(['status' => 'processing']);
        }

        // Define the CSV file path
        $csvFilePath = storage_path('app/public/withdrawals.csv');

        // Open the CSV file for writing
        $csvFile = fopen($csvFilePath, 'w');

        // Write the header row to the CSV file
        fputcsv($csvFile, ["Account Number", "Bank", "Amount", "Narration"]);

        // Write data rows to the CSV file
        foreach ($withdrawals as $withdrawal) {
            $worker = $withdrawal->worker;
            fputcsv($csvFile, [
                $worker->account_number,
                $worker->bank_code,
                $withdrawal->amount,
                'Earning payment from holeify to ' . $worker->name
            ]);
            
            // Update withdrawal status to 'processing'
            // $withdrawal->update(['status' => 'processing']);
        }

        // Close the CSV file
        fclose($csvFile);

        return response()->json(["message"=> "payout processing", "csv_path"=> "storage/withdrawals.csv"]);
    }

    public function workersToPay(Request $request){
        return response()->json(["workers_to_pay"=> $this->getWorkersToPay()]);
    }

    // private function performPaystackTransfer($worker, $amount)
    // {
    //     $paystack = new Paystack(env('PAYSTACK_TEST_SECRET_KEY')); // Initialize Paystack instance

    //     // Create a transfer recipient using worker's account details
    //     $recipient = $paystack->transferrecipient->create([
    //         'type' => 'nuban',
    //         'name' => $worker->name, // Replace with worker's name
    //         'account_number' => $worker->account_number,
    //         'bank_code' => $worker->bank_code,
    //         'currency' => 'NGN', // Replace with appropriate currency code
    //     ]);

    //     // return $recipient;

    //     // Initiate the transfer using the recipient and amount
    //     $transfer = $paystack->transfer->recipient($recipient->data->recipient_code)->send([
    //         'amount' => $amount * 100, // Convert amount to kobo
    //         'reason' => 'Bulk payment to worker',
    //     ]);

    //     // Check if the transfer status is successful
    //     if ($transfer->data->status === 'success') {
    //         // echo "success";
    //         // return response()->json($transfer->data);
    //         return true;
    //     } else {
    //         echo "error";
    //         // return response()->json($transfer->data);
    //         return false;
    //     }
    // }

    public function initiateTransfer($_data)
    {
        // return response()->json($_data['bank_name']);
        // $flutterwaveApiKey = 'FLWSECK_TEST-d43875915eac57295c052dcd6673d2ed-X';
        if(env("APP_DEBUG")){
            $api_key = env("FLUTTERWAVE_TEST_SECRET_KEY");
        }else{
            $api_key = env("FLUTTERWAVE_SECRET_KEY");
        }

        $recipientData = [
            'account_number' => $_data["account_number"],
            'bank_code' => $_data["bank_code"],
            'currency' => 'NGN',
            'beneficiary_name' => $_data["name"],
            'bank_name' => $_data["bank_name"],
            // Add other recipient details as needed
        ];

        // return response()->json($recipientData);

        $url = 'https://api.flutterwave.com/v3/transfers';
        $headers = [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ];

        $data = [
            'amount' => $_data["amount"], // Replace with the actual amount in kobo
            'currency' => 'NGN',
            'recipient' => $recipientData,
            'narration' => 'Bulk payment to worker',
            'account_number' => $_data["account_number"],
            'account_bank' => $_data["bank_code"],
            'bank_name' => $_data["bank_name"],
            'currency' => 'NGN',
            'beneficiary_name' => $_data["name"],
            // 'account_name' => $_data["acco"]
        ];

        // return response()->json($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !config('app.debug'));

        $response = curl_exec($ch);
        // return response()->json($data);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // return response()->json($response);

        curl_close($ch);

        if ($statusCode === 200) {
            $responseData = json_decode($response, true);
            // Transfer was successful
            return response()->json([$responseData, 'success'=> true]);
        } else {
            // Transfer failed, handle error
            return response()->json(['error' => 'Transfer failed', 'message' => 'Error message here', 'success'=> false]);
        }
    }

    private function getWorkersToPay()
    {
        $workers_to_pay = Worker::joinSub(function ($query) {
            $query->select('worker_id', DB::raw('SUM(earning) AS total_earning'))
                ->from('done_tasks')
                ->where('paid', 0)
                ->groupBy('worker_id')
                ->havingRaw('total_earning >= ?', [250]);
        }, 'unpaid_earnings', function ($join) {
            $join->on('workers.id', '=', 'unpaid_earnings.worker_id');
        })
        ->join('done_tasks', 'workers.id', '=', 'done_tasks.worker_id') // Join to get done_tasks
        ->select('workers.*', 'unpaid_earnings.total_earning', 'done_tasks.id as done_task_id')// Select worker columns, total_earning, and done_tasks ID
        ->with('done_tasks') 
        ->get();

        return $workers_to_pay;
    }

    public function updateWithdrawalsStatus(Request $request)
    {
        $status = $request->status;
        $IDs = $request->IDs;

        withdrawal::whereIn('id', $IDs)->update(['status' => $status]);

        // if ($status === 'approved') {
        //     // Get the submitted tasks with the updated status as "approved"
        //     $approvedTasks = SubmittedTask::whereIn('id', $IDs)->where('status', 'approved')->get();

        //     // Move each approved task to the DoneTask table and delete from SubmittedTask
        //     foreach ($approvedTasks as $task) {
        //         DoneTask::create([
        //             'task_id' => $task->task_id,
        //             'worker_id' => $task->worker_id,
        //             'earning' => $task->reward, // Assuming 'reward' field represents earning in SubmittedTask
        //             'paid' => false, // Assuming 'paid' field is a boolean indicating if the worker has been paid for the task in DoneTask
        //         ]);

        //         // Delete the task from SubmittedTask
        //         $task->delete();
        //     }
        // }

        // Return the updated SubmittedTasks related to the worker_id
        return response()->json($this->get_withdrawal_details(), 200);
    }


    public function queryWithdrawals(Request $request)
    {
        return response()->json(withdrawal::where($request->all())->get());
    }

}

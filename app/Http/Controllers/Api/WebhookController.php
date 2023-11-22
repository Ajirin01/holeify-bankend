<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade
use App\Models\TaskCreateTempData;

use App\Models\Worker;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\DoneTask;
use DB;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Process the webhook payload
        // $data = [
        //     'task_data' => json_encode($request->all()),
        //     'transaction_id' => "11111111111111"
        // ];

        // TaskCreateTempData::create($data);

        try {
            // Verify the webhook signature (implement this function)
            $isValidSignature = $this->verifySignature($request);

            if (!$isValidSignature) {
                Log::warning('Invalid webhook signature');
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }

            // $webhook_response = json_decode(
            //     '{
            //         "event":"transfer.completed",
            //         "event.type":"Transfer",
            //         "data":
            //         {
            //             "id":63940128,
            //             "account_number":"0179428998",
            //             "bank_name":"GTBANK PLC",
            //             "bank_code":"058",
            //             "fullname":"OLAGOKE, MUBARAK ISHOLA",
            //             "created_at":"2023-11-21T16:42:22.000Z",
            //             "currency":"NGN",
            //             "debit_currency":"NGN",
            //             "amount":100,"fee":10.75,
            //             "status":"SUCCESSFUL",
            //             "reference":"7c5cb3991779b431",
            //             "meta":null,
            //             "narration":"Transfer fro flutter to Olagoke Mubarak",
            //             "approver":null,
            //             "complete_message":"Transaction was successful",
            //             "requires_approval":0,
            //             "is_approved":1
            //         }
            //     }'
            // );

            $webhook_response = $request->all();
            
            $type = "event.type";
            // return response()->json($webhook_response->$type);

            if($webhook_response->$type === "Transfer"){
                $account_number = $webhook_response->data->account_number;
                $bank_code = $webhook_response->data->bank_code;

                if($webhook_response->data->status === "SUCCESSFUL"){
                    // return response()->json(Withdrawal::where("worker.account_number", $account_number)->first());
                    $withdrawal = Withdrawal::join('workers', 'withdrawals.worker_id', '=', 'workers.id')
                                            ->where(['workers.account_number' => $account_number, 'workers.bank_code' => $bank_code])
                                            ->select('withdrawals.id', 'workers.id as worker_id', 'amount', 'paid', 'status', 'done_tasks', 'name', 'user_id', 'account_number', 'bank_code', 'bank_name')
                                            ->orderBy('withdrawals.id', 'desc')
                                            ->first();

                    $done_tasks = json_decode($withdrawal->done_tasks);
                    $withdrawal_id = $withdrawal->id;

                    $withdrawal_to_update = Withdrawal::find($withdrawal_id);
                    foreach ($done_tasks as $key => $done_task) {
                        $done_task_object = DoneTask::find($done_task->id);
                        $done_task_object->update(['paid'=> true]);
                        // return response()->json($done_task_object);
                    }

                    $withdrawal_to_update->update(['status' => 'paid']);

                    // return response()->json([$done_tasks, $withdrawal]);
                }
            }

            // Process the webhook payload
            $data = [
                'task_data' => json_encode($request->all()),
                'transaction_id' => "11111111111111"
            ];
            

            TaskCreateTempData::create($data);

            Log::info('Webhook handled successfully');
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error handling webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', $e->getMessage()], 500);
        }
    }

    private function verifySignature(Request $request)
    {
        // Implement your signature verification logic here
        // You will need to compare the Flutterwave signature with your calculated signature
        // Refer to Flutterwave documentation for details on signature verification

        // For simplicity, assuming signature is valid in this example
        return true;
    }
}

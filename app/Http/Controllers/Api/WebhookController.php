<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log facade
use App\Models\TaskCreateTempData;

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
            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
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

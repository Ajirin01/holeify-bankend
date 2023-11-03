<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        // Get the transaction reference from the request data
        $transactionReference = $request->input('tx_ref');
        // return response()->json($transactionReference);

        if(env('APP_DEBUG')){
            $api_key = env("FLUTTERWAVE_TEST_SECRET_KEY");
        }else{
            $api_key = env("FLUTTERWAVE_SECRET_KEY");
        }

        // Set the endpoint URL for the Flutterwave API
        $url = 'https://api.flutterwave.com/v3/transactions/' . $transactionReference . '/verify';

        // Create a new cURL handle
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ". $api_key,
            ],
            CURLOPT_SSL_VERIFYPEER => !config('app.debug'),
        ]);

        // Send the request and get the response
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            // Handle the error accordingly
            return response()->json(['message' => 'cURL Error: ' . $error], 500);
        }

        // Close the cURL handle
        curl_close($curl);

        // Decode the JSON response
        $paymentData = json_decode($response, true);

        // Check if the payment was successful
        if ($paymentData['status'] === 'success' && $paymentData['data']['status'] === 'successful') {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'error'], 200);
        }

        // return response()->json($paymentData);
    }

}

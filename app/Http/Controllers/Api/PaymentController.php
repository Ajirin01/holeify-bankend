namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        // Get the transaction reference from the request data
        $transactionReference = $request->input('trxref');

        // Set the endpoint URL for the Paystack API
        <!-- $url = 'https://api.paystack.co/transaction/verify/' . $transactionReference;

        // Create a new cURL handle
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '. env('PAYSTACK_KEY_TEST')
            ]
        ]);

        // Send the request and get the response
        $response = curl_exec($curl);

        // Close the cURL handle
        curl_close($curl);

        // Decode the JSON response
        $paymentData = json_decode($response, true);

        // Check if the payment was successful
        if ($paymentData['data']['status'] === 'success') {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'error'], 200);
        } -->
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        $paymentHistories = PaymentHistory::all();
        return response()->json($paymentHistories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => 'required',
            'worker_name' => 'required',
            'date_requested' => 'required|date',
            'date_paid' => 'required|date',
            'status' => 'required',
            'amount' => 'required|numeric'
        ]);

        $paymentHistory = PaymentHistory::create($validatedData);

        return response()->json($paymentHistory, 201);
    }

    public function show($id)
    {
        $paymentHistory = PaymentHistory::find($id);

        if (!$paymentHistory) {
            return response()->json(['error' => 'Payment history not found'], 404);
        }

        return response()->json($paymentHistory);
    }

    public function update(Request $request, $id)
    {
        $paymentHistory = PaymentHistory::find($id);

        if (!$paymentHistory) {
            return response()->json(['error' => 'Payment history not found'], 404);
        }

        $validatedData = $request->validate([
            'worker_id' => 'required',
            'worker_name' => 'required',
            'date_requested' => 'required|date',
            'date_paid' => 'required|date',
            'status' => 'required',
            'amount' => 'required|numeric'
        ]);

        $paymentHistory->fill($validatedData);
        $paymentHistory->save();

        return response()->json($paymentHistory);
    }

    public function destroy($id)
    {
        $paymentHistory = PaymentHistory::find($id);

        if (!$paymentHistory) {
            return response()->json(['error' => 'Payment history not found'], 404);
        }

        $paymentHistory->delete();

        return response()->json(['message' => 'Payment history deleted']);
    }
}
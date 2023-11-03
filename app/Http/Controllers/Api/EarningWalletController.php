<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EarningWallet;

class EarningWalletController extends Controller
{
    public function index()
    {
        $earningWallets = EarningWallet::all();
        return response()->json($earningWallets);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => 'required',
            'worker_name' => 'required',
            'earning' => 'required',
            'status' => 'required'
        ]);

        $earningWallet = EarningWallet::create($validatedData);

        return response()->json($earningWallet, 201);
    }

    public function show($id)
    {
        $earningWallet = EarningWallet::find($id);

        if (!$earningWallet) {
            return response()->json(['error' => 'Earning wallet not found'], 404);
        }

        return response()->json($earningWallet);
    }

    public function update(Request $request, $id)
    {
        $earningWallet = EarningWallet::find($id);

        if (!$earningWallet) {
            return response()->json(['error' => 'Earning wallet not found'], 404);
        }

        $validatedData = $request->validate([
            'worker_id' => 'required',
            'worker_name' => 'required',
            'earning' => 'required',
            'status' => 'required'
        ]);

        $earningWallet->fill($validatedData);
        $earningWallet->save();

        return response()->json($earningWallet);
    }

    public function destroy($id)
    {
        $earningWallet = EarningWallet::find($id);

        if (!$earningWallet) {
            return response()->json(['error' => 'Earning wallet not found'], 404);
        }

        $earningWallet->delete();

        return response()->json(['message' => 'Earning wallet deleted']);
    }
}
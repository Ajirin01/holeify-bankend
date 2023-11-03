<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FundWallet;

class FundWalletController extends Controller
{
    public function index()
    {
        $fundWallets = FundWallet::all();
        return response()->json($fundWallets);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'requester_id' => 'required',
            'requester_name' => 'required',
            'fund' => 'required',
            'status' => 'required'
        ]);

        $fundWallet = FundWallet::create($validatedData);

        return response()->json($fundWallet, 201);
    }

    public function show($id)
    {
        $fundWallet = FundWallet::find($id);

        if (!$fundWallet) {
            return response()->json(['error' => 'Fund wallet not found'], 404);
        }

        return response()->json($fundWallet);
    }

    public function update(Request $request, $id)
    {
        $fundWallet = FundWallet::find($id);

        if (!$fundWallet) {
            return response()->json(['error' => 'Fund wallet not found'], 404);
        }

        $validatedData = $request->validate([
            'requester_id' => 'required',
            'requester_name' => 'required',
            'fund' => 'required',
            'status' => 'required'
        ]);

        $fundWallet->fill($validatedData);
        $fundWallet->save();

        return response()->json($fundWallet);
    }

    public function destroy($id)
    {
        $fundWallet = FundWallet::find($id);

        if (!$fundWallet) {
            return response()->json(['error' => 'Fund wallet not found'], 404);
        }

        $fundWallet->delete();

        return response()->json(['message' => 'Fund wallet deleted']);
    }
}
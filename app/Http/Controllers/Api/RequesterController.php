<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requester;

class RequesterController extends Controller
{
    public function index()
    {
        $requesters = Requester::all();
        return response()->json($requesters);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'photo' => 'required'
        ]);

        $requester = Requester::create($validatedData);

        return response()->json($requester, 201);
    }

    public function show($id)
    {
        $requester = Requester::find($id);

        if (!$requester) {
            return response()->json(['error' => 'Requester not found'], 404);
        }

        return response()->json($requester);
    }

    public function update(Request $request, $id)
    {
        $requester = Requester::find($id);

        if (!$requester) {
            return response()->json(['error' => 'Requester not found'], 404);
        }

        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'photo' => 'required'
        ]);

        $requester->fill($validatedData);
        $requester->save();

        return response()->json($requester);
    }

    public function destroy($id)
    {
        $requester = Requester::find($id);

        if (!$requester) {
            return response()->json(['error' => 'Requester not found'], 404);
        }

        $requester->delete();

        return response()->json(['message' => 'Requester deleted']);
    }
}
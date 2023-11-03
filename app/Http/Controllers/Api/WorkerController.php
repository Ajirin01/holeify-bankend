<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return response()->json(['data' => $workers], 200);
    }

    public function show($id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['message' => 'Worker not found'], 404);
        }
        return response()->json(['data' => $worker], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required'
        ]);

        $worker = new Worker();
        $worker->user_id = $request->user_id;
        $worker->name = $request->name;
        $worker->save();

        return response()->json(['data' => $worker], 201);
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['message' => 'Worker not found'], 404);
        }

        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required'
        ]);

        $worker->user_id = $request->user_id;
        $worker->name = $request->name;
        $worker->save();

        return response()->json(['data' => $worker], 200);
    }

    public function destroy($id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['message' => 'Worker not found'], 404);
        }
        $worker->delete();
        return response()->json(['message' => 'Worker deleted'], 200);
    }
}
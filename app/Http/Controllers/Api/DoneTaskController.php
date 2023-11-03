<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DoneTask;

class DoneTaskController extends Controller
{
    public function index()
    {
        $doneTasks = DoneTask::all();
        return response()->json($doneTasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => 'required',
            'task_id' => 'required',
            'earning' => 'required'
        ]);

        $doneTask = DoneTask::create($validatedData);

        return response()->json($doneTask, 201);
    }

    public function show($id)
    {
        $doneTask = DoneTask::find($id);

        if (!$doneTask) {
            return response()->json(['error' => 'Done task not found'], 404);
        }

        return response()->json($doneTask);
    }

    public function update(Request $request, $id)
    {
        $doneTask = DoneTask::find($id);

        if (!$doneTask) {
            return response()->json(['error' => 'Done task not found'], 404);
        }

        $validatedData = $request->validate([
            'worker_id' => 'required',
            'task_id' => 'required',
            'earning' => 'required'
        ]);

        $doneTask->fill($validatedData);
        $doneTask->save();

        return response()->json($doneTask);
    }

    public function destroy($id)
    {
        $doneTask = DoneTask::find($id);

        if (!$doneTask) {
            return response()->json(['error' => 'Done task not found'], 404);
        }

        $doneTask->delete();

        return response()->json(['message' => 'Done task deleted']);
    }

    public function getDoneTask(Request $request)
    {
        // return response()->json(DoneTask::where($request->all())->where("paid", false)->get(), 200);
        return response()->json(DoneTask::where($request->all())->get(), 200);
    }

    public function updateDoneTasksStatus(Request $request)
    {
        $status = $request->status;
        $IDs = $request->IDs;
        $worker_id = $request->worker_id;

        DoneTask::whereIn('id', $IDs)->update(['status'=> $status]);

        return response()->json(DoneTask::where('worker_id', $worker_id)->get(), 200);
    }
}
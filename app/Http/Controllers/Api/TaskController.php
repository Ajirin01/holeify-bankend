<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

use Pusher\Pusher;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function queryTask(Request $request){
        $tasks = Task::where($request->all())->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'category_id' => 'required',
            'requester_id' => 'required',
            'total_need' => 'required|numeric',
            'total_done' => 'required|numeric',
            'reward' => 'required|numeric',
            'link' => 'required'
        ]);

        $task = Task::create($validatedData);

        // publish a notification to Pusher
        // $pusher = new Pusher(
        //     config('broadcasting.connections.pusher.key'),
        //     config('broadcasting.connections.pusher.secret'),
        //     config('broadcasting.connections.pusher.app_id'),
        //     [
        //         'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        //         'encrypted' => false
        //     ]
        // );
        
        // $pusher->trigger('tasks', 'task-added', $task);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'category_id' => 'required',
            'requester_id' => 'required',
            'total_need' => 'required|numeric',
            'total_done' => 'required|numeric',
            'reward' => 'required|numeric',
            'link' => 'required'
        ]);

        $task->fill($validatedData);
        $task->save();

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}
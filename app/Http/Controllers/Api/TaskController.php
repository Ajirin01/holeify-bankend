<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;

use Pusher\Pusher;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function queryTask(Request $request){
        if($request->has('query')){
            $query = $request->input('query');
            $status = $request->input('status');

            // $tasks = Task::whereRaw($query)
            //             ->where('status', $status)
            //             ->orderBy('reward', 'desc')
            //             ->get();

            $tasks = Task::whereRaw($query)
                        ->where('status', $status)
                        ->join('requesters', 'tasks.requester_id', '=', 'requesters.id')
                        ->join('users', 'requesters.user_id', '=', 'users.id')
                        ->orderByRaw("CASE WHEN users.role = 'admin' THEN 0 ELSE 1 END, reward DESC")
                        ->select('tasks.*', 'users.role') // Select only the columns from the 'tasks' table
                        ->get();

        }else{
            $tasks = Task::where($request->all())->get();
        }

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

        $task_category = Category::find($request->category_id);

        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => false
            ]
        );

        if($task_category->name != "custom"){
            // publish a notification to Pusher
            $pusher->trigger('tasks', 'task-added', [$task]);
        }

        

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

    public function updateTasksStatus(Request $request)
    {
        $status = $request->status;
        $IDs = $request->IDs;
        $requester_id = $request->requester_id;

        Task::whereIn('id', $IDs)->update(['status' => $status]);

        // publish a notification to Pusher
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => false
            ]
        );

        if ($status === 'approved') {
            // Get the submitted tasks with the updated status as "approved"
            $approvedTasks = Task::whereIn('id', $IDs)->where('status', 'approved')->get();
            
            $pusher->trigger('tasks', 'task-added', $approvedTasks);
        }

        // Return the updated SubmittedTasks related to the worker_id
        return response()->json(Task::all(), 200);
    }
}
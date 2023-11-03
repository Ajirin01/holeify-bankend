<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DoneTask;
use App\Models\SubmittedTask;
use App\Models\Task;
use App\Models\User;

class SubmittedTaskController extends Controller
{
    public function index()
    {
        $submittedTasks = SubmittedTask::all();
        return response()->json($submittedTasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required', 
            'worker_id' => 'required', 
            'requester_id' => 'required', 
            'reward' => 'required', 
            'prove_photo' => 'required', 
            'status' => 'required'
        ]);

        $submittedTask = SubmittedTask::create($validatedData);

        $task = Task::find($request->task_id);
        $old_total_task_done = $task->total_done;
        $new_total_task_done = $old_total_task_done + 1;

        $task->total_done = $new_total_task_done;
        $task->save();

        return response()->json($submittedTask, 201);
    }

    public function show($id)
    {
        $submittedTask = SubmittedTask::find($id);

        if (!$submittedTask) {
            return response()->json(['error' => 'Done task not found'], 404);
        }

        return response()->json($submittedTask);
    }

    public function update(Request $request, $id)
    {
        $submittedTask = SubmittedTask::find($id);

        if (!$submittedTask) {
            return response()->json(['error' => 'Done task not found'], 404);
        }

        $validatedData = $request->validate([
            'task_id' => 'required', 
            'worker_id' => 'required', 
            'requester_id' => 'required', 
            'reward' => 'required', 
            'prove_photo' => 'required', 
            'status' => 'required'
        ]);

        $submittedTask->fill($validatedData);
        $submittedTask->save();

        return response()->json($submittedTask);
    }

    public function destroy($id)
    {
        $submittedTask = SubmittedTask::find($id);

        if (!$submittedTask) {
            return response()->json(['error' => 'Submitted task not found'], 404);
        }

        $submittedTask->delete();

        return response()->json(['message' => 'Submitted task deleted']);
    }

    public function getSubmittedTask(Request $request)
    {
        return response()->json(SubmittedTask::where($request->all())->get(), 200);
    }

    public function updateSubmittedTasksStatus(Request $request)
    {
        $status = $request->status;
        $IDs = $request->IDs;
        $requester_id = $request->requester_id;

        SubmittedTask::whereIn('id', $IDs)->update(['status' => $status]);

        if ($status === 'approved') {
            // Get the submitted tasks with the updated status as "approved"
            $approvedTasks = SubmittedTask::whereIn('id', $IDs)->where('status', 'approved')->get();

            // Move each approved task to the DoneTask table and delete from SubmittedTask
            foreach ($approvedTasks as $task) {
                DoneTask::create([
                    'task_id' => $task->task_id,
                    'worker_id' => $task->worker_id,
                    'earning' => $task->reward, // Assuming 'reward' field represents earning in SubmittedTask
                    'paid' => false, // Assuming 'paid' field is a boolean indicating if the worker has been paid for the task in DoneTask
                ]);

                // Delete the task from SubmittedTask
                $task->delete();
            }
        }

        // Return the updated SubmittedTasks related to the worker_id
        return response()->json(SubmittedTask::where('requester_id', User::find($requester_id)->requester->id)->get(), 200);
    }


}
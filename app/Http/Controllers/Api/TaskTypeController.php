<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskType;

class TaskTypeController extends Controller
{
    public function index()
    {
        $tasks = TaskType::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
        ]);

        $taskType = TaskType::create($validatedData);

        return response()->json($taskType, 201);
    }

    public function show($id)
    {
        $taskType = TaskType::find($id);

        if (!$taskType) {
            return response()->json(['error' => 'TaskType not found'], 404);
        }

        return response()->json($taskType);
    }

    public function update(Request $request, $id)
    {
        $taskType = TaskType::find($id);

        if (!$taskType) {
            return response()->json(['error' => 'TaskType not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required',
            'category_id' => 'required'
        ]);

        $taskType->fill($validatedData);
        $taskType->save();

        return response()->json($taskType);
    }

    public function destroy($id)
    {
        $taskType = TaskType::find($id);

        if (!$taskType) {
            return response()->json(['error' => 'TaskType not found'], 404);
        }

        $taskType->delete();

        return response()->json(['message' => 'TaskType deleted']);
    }
}
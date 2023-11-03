<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FeaturedTask;

class FeaturedTaskController extends Controller
{
    public function index()
    {
        $featuredTasks = FeaturedTask::all();
        return response()->json($featuredTasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required',
            'subscription_category_id' => 'required',
            'status' => 'required'
        ]);

        $featuredTask = FeaturedTask::create($validatedData);

        return response()->json($featuredTask, 201);
    }

    public function show($id)
    {
        $featuredTask = FeaturedTask::find($id);

        if (!$featuredTask) {
            return response()->json(['error' => 'Featured task not found'], 404);
        }

        return response()->json($featuredTask);
    }

    public function update(Request $request, $id)
    {
        $featuredTask = FeaturedTask::find($id);

        if (!$featuredTask) {
            return response()->json(['error' => 'Featured task not found'], 404);
        }

        $validatedData = $request->validate([
            'task_id' => 'required',
            'subscription_category_id' => 'required',
            'status' => 'required'
        ]);

        $featuredTask->fill($validatedData);
        $featuredTask->save();

        return response()->json($featuredTask);
    }

    public function destroy($id)
    {
        $featuredTask = FeaturedTask::find($id);

        if (!$featuredTask) {
            return response()->json(['error' => 'Featured task not found'], 404);
        }

        $featuredTask->delete();

        return response()->json(['message' => 'Featured task deleted']);
    }
}
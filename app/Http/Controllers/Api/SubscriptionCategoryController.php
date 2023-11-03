<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionCategory;

class SubscriptionCategoryController extends Controller
{
    public function index()
    {
        $subscriptionCategories = SubscriptionCategory::all();
        return response()->json($subscriptionCategories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        $subscriptionCategory = SubscriptionCategory::create($validatedData);

        return response()->json($subscriptionCategory, 201);
    }

    public function show($id)
    {
        $subscriptionCategory = SubscriptionCategory::find($id);

        if (!$subscriptionCategory) {
            return response()->json(['error' => 'Subscription category not found'], 404);
        }

        return response()->json($subscriptionCategory);
    }

    public function update(Request $request, $id)
    {
        $subscriptionCategory = SubscriptionCategory::find($id);

        if (!$subscriptionCategory) {
            return response()->json(['error' => 'Subscription category not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        $subscriptionCategory->fill($validatedData);
        $subscriptionCategory->save();

        return response()->json($subscriptionCategory);
    }

    public function destroy($id)
    {
        $subscriptionCategory = SubscriptionCategory::find($id);

        if (!$subscriptionCategory) {
            return response()->json(['error' => 'Subscription category not found'], 404);
        }

        $subscriptionCategory->delete();

        return response()->json(['message' => 'Subscription category deleted']);
    }
}
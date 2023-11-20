<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TaskCreateTempData;

class TaskTempDataController extends Controller
{
    public function createTaskTempData(Request $request){
        return response()->json(TaskCreateTempData::create($request->all()));
    }

    public function getTaskTempData($transaction_id){
        // return response()->json(TaskCreateTempData::where('transaction_id', $transaction_id)->first());
        return response()->json([
            'task_data' => json_decode(TaskCreateTempData::where('transaction_id', $transaction_id)->value('task_data')),
            'transaction_id' => TaskCreateTempData::where('transaction_id', $transaction_id)->value('transaction_id'),
            'created_at' => TaskCreateTempData::where('transaction_id', $transaction_id)->value('created_at')
        ]);
        
    }
}

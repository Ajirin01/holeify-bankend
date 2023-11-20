<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// user logout route
Route::group(['prefix'=> 'user', 'middleware'=> 'auth:sanctum'], function(){
    Route::get('/', [\App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/all', [\App\Http\Controllers\Api\AuthController::class, 'all']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('update', [\App\Http\Controllers\Api\AuthController::class, 'updateAccount']);
});

// password reset endpoints
Route::post('password-email', 'App\Http\Controllers\Api\PasswordResetController@sendResetLinkEmail');
Route::post('password-reset', 'App\Http\Controllers\Api\PasswordResetController@reset');
Route::post('token-verify', 'App\Http\Controllers\Api\PasswordResetController@find');


// Users registration route
Route::group(['prefix'=> 'user'], function(){
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
});

Route::apiResource('categories', 'App\Http\Controllers\Api\CategoryController');
Route::apiResource('done-tasks', 'App\Http\Controllers\Api\DoneTaskController');
Route::post('get-done-tasks', [App\Http\Controllers\Api\DoneTaskController::class, 'getDoneTask']);
Route::post('update-done-tasks-status', [App\Http\Controllers\Api\DoneTaskController::class, 'updateDoneTasksStatus']);

Route::apiResource('submitted-tasks', 'App\Http\Controllers\Api\SubmittedTaskController');
Route::post('get-submitted-tasks', [App\Http\Controllers\Api\SubmittedTaskController::class, 'getSubmittedTask']);
Route::post('update-submitted-tasks-status', [App\Http\Controllers\Api\SubmittedTaskController::class, 'updateSubmittedTasksStatus']);

// Route::post('delete-submitted-tasks', [App\Http\Controllers\Api\SubmittedTaskController::class, 'updateSubmittedTasksStatus']);


Route::apiResource('earning-wallets', 'App\Http\Controllers\Api\EarningWalletController');
Route::apiResource('featured-tasks', 'App\Http\Controllers\Api\FeaturedTaskController');
Route::apiResource('fund-wallets', 'App\Http\Controllers\Api\FundWalletController');
Route::apiResource('payment-histories', 'App\Http\Controllers\Api\PaymentHistoryController');
Route::apiResource('requesters', 'App\Http\Controllers\Api\RequesterController');
Route::apiResource('subscription-categories', 'App\Http\Controllers\Api\SubscriptionCategoryController');

Route::apiResource('tasks', 'App\Http\Controllers\Api\TaskController');
Route::post('/queryTask', [App\Http\Controllers\Api\TaskController::class, 'queryTask']);
Route::post('update-tasks-status', [App\Http\Controllers\Api\TaskController::class, 'updateTasksStatus']);

Route::apiResource('task-types', 'App\Http\Controllers\Api\TaskTypeController');
Route::apiResource('workers', 'App\Http\Controllers\Api\WorkerController');

Route::post('verify-payment', [App\Http\Controllers\Api\PaymentController::class, 'verify']);

Route::post('check-account-detail-exist', [App\Http\Controllers\Api\WithdrawalController::class, 'check_account_detail_exist']);
Route::post('resolve-account', [App\Http\Controllers\Api\WithdrawalController::class, 'resolve_account']);
Route::post('save-account-detail', [App\Http\Controllers\Api\WithdrawalController::class, 'save_account_detail']);
Route::post('workers-to-pay', [App\Http\Controllers\Api\WithdrawalController::class, 'workersToPay']);
Route::post('create-withdrawal-details', [App\Http\Controllers\Api\WithdrawalController::class, 'create_withdrawal_details']);
Route::get('process-payment-to-workers', [App\Http\Controllers\Api\WithdrawalController::class, 'processPaymentToWorkers']);
Route::post('withdrawal', [App\Http\Controllers\Api\WithdrawalController::class, 'withdrawal']);
Route::post('get-withdrawal-details', [App\Http\Controllers\Api\WithdrawalController::class, 'get_withdrawal_details']);
Route::post('query-withdrawals', [App\Http\Controllers\Api\WithdrawalController::class, 'queryWithdrawals']);

Route::post('update-withdrawal-status', [App\Http\Controllers\Api\WithdrawalController::class, 'updateWithdrawalsStatus']);

Route::post('create-task-temp-data', [App\Http\Controllers\Api\TaskTempDataController::class, 'createTaskTempData']);
Route::get('get-task-temp-data/{transaction_id}', [App\Http\Controllers\Api\TaskTempDataController::class, 'getTaskTempData']);


Route::post('webhook', [App\Http\Controllers\Api\WebhookController::class, 'handle']);







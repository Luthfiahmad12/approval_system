<?php

use App\Http\Controllers\ApprovalStageController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/statuses', [StatusController::class, 'store']);
Route::post('/approvers', [ApproverController::class, 'store']);
Route::post('/approval-stages', [ApprovalStageController::class, 'store']);
Route::put('/approval-stages/{id}', [ApprovalStageController::class, 'update']);
Route::post('/expenses', [ExpenseController::class, 'store']);
Route::patch('/expenses/{id}/approve', [ExpenseController::class, 'patchApprove']);
Route::get('/expenses/{id}', [ExpenseController::class, 'show']);

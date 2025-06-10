<?php

use App\Http\Controllers\Api\Admin\UserCreateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\SalarySlipController;
use App\Http\Controllers\Api\TeamController;


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('auth/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::put('/profile', [AuthenticationController::class, 'updateProfile']);
    Route::post('/profile/photo', [AuthenticationController::class, 'updateProfilePhoto']);
    Route::post('/users', [UserCreateController::class, 'store'])->name('api.users.store');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/change-password', [AuthenticationController::class, 'changePassword']);
    Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);

    //Dashboard API
    Route::get('/dashboard', [DashboardController::class, 'index']);
    //Lead Api

    Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::post('/leads/{lead}', [LeadController::class, 'update']);
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);

    // Task API (Add this for full CRUD)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    //attendance
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::post('/attendances', [AttendanceController::class, 'store']);
    Route::post('/attendances/{attendance}', [AttendanceController::class, 'update']);

    //Team view api
    Route::get('/teams', [TeamController::class, 'index']);

    //leave api
    Route::get('/leaves', [LeaveController::class, 'index']);
    Route::post('/leaves', [LeaveController::class, 'store']);
    //salary api
    Route::get('/salary-slips', [SalarySlipController::class, 'index']);
    Route::get('/salary-slips/{id}/download', [SalarySlipController::class, 'downloadPdf']);

});

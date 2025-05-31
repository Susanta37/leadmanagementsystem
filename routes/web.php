<?php

use App\Http\Controllers\AdminController\AdminController;
use App\Http\Controllers\OpearationController\OperationDashboardController;
use App\Http\Controllers\TLController\TLDashboardController;
use App\Http\Controllers\EmployeeController\EmployeeDashboardController as EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login page
Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Dashboard redirection based on designation
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->designation) {
        case 'employee':
            return redirect()->route('employee.dashboard');
        case 'team_lead':
            return redirect()->route('team_lead.dashboard');
        case 'operations':
            return redirect()->route('operations.dashboard');
        case 'admin':
            return redirect()->route('admin.dashboard');
        default:
            abort(403, 'Unauthorized action.');
    }
})->name('dashboard');

// Authenticated routes (provided by Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Employee routes (accessible only by users with designation 'employee')
Route::middleware(['auth', 'designation:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/leads', [EmployeeController::class, 'indexLeads'])->name('leads.index');
    Route::get('/leads/create', [EmployeeController::class, 'createLead'])->name('leads.create');
    Route::post('/leads', [EmployeeController::class, 'storeLead'])->name('leads.store');
    Route::get('/leads/{lead}/edit', [EmployeeController::class, 'editLead'])->name('leads.edit');
    Route::patch('/leads/{lead}', [EmployeeController::class, 'updateLead'])->name('leads.update');
    Route::get('/tasks', [EmployeeController::class, 'indexTasks'])->name('tasks.index');
    Route::get('/team', [EmployeeController::class, 'indexTeam'])->name('team.index');
    Route::get('/attendance', [EmployeeController::class, 'indexAttendance'])->name('attendance.index');
    Route::post('/attendance/check-in', [EmployeeController::class, 'checkIn'])->name('attendance.check_in');
    Route::post('/attendance/check-out', [EmployeeController::class, 'checkOut'])->name('attendance.check_out');
    Route::get('/notifications', [EmployeeController::class, 'indexNotifications'])->name('notifications.index');
    Route::get('/setting', [EmployeeController::class, 'indexSetting'])->name('settings.index');
    Route::patch('/setting', [EmployeeController::class, 'updateSetting'])->name('settings.update');
});

// Team Lead routes (accessible only by users with designation 'team_lead')
Route::middleware(['auth', 'designation:team_lead'])->prefix('team-lead')->name('team_lead.')->group(function () {
    Route::get('/dashboard', [TLDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [TLDashboardController::class, 'indexEmployees'])->name('employees.index');
    Route::get('/employees/create', [TLDashboardController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [TLDashboardController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/leads', [TLDashboardController::class, 'indexLeads'])->name('leads.index');
    Route::post('/leads/{lead}/approve', [TLDashboardController::class, 'approveLead'])->name('leads.approve');
    Route::post('/leads/{lead}/reject', [TLDashboardController::class, 'rejectLead'])->name('leads.reject');
    Route::get('/tasks', [TLDashboardController::class, 'indexTasks'])->name('tasks.index');
    Route::get('/tasks/create', [TLDashboardController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [TLDashboardController::class, 'storeTask'])->name('tasks.store');
    Route::post('/tasks/bulk-assign', [TLDashboardController::class, 'bulkAssignTasks'])->name('tasks.bulk_assign');
    Route::get('/notifications', [TLDashboardController::class, 'indexNotifications'])->name('notifications.index');
});

// Operations routes (accessible only by users with designation 'operations')
Route::middleware(['auth', 'designation:operations'])->prefix('operations')->name('operations.')->group(function () {
    Route::get('/dashboard', [OperationDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/leads', [OperationDashboardController::class, 'indexLeads'])->name('leads.index');
    Route::post('/leads/{lead}/complete', [OperationDashboardController::class, 'completeLead'])->name('leads.complete');
    Route::get('/notifications', [OperationDashboardController::class, 'indexNotifications'])->name('notifications.index');
});

// Admin routes (accessible only by users with designation 'admin')
Route::middleware(['auth', 'designation:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/leads', [AdminController::class, 'indexLeads'])->name('leads.index');
    Route::get('/tasks', [AdminController::class, 'indexTasks'])->name('tasks.index');
    Route::get('/attendances', [AdminController::class, 'indexAttendances'])->name('attendances.index');
    Route::get('/notifications', [AdminController::class, 'indexNotifications'])->name('notifications.index');
});

require __DIR__.'/auth.php';
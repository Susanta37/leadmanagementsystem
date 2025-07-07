<?php

use App\Http\Controllers\AdminController\AdminController;
use App\Http\Controllers\OpearationController\OperationDashboardController;
use App\Http\Controllers\TLController\TeamLeadReportController;
use App\Http\Controllers\TLController\TLDashboardController;
use App\Http\Controllers\EmployeeController\EmployeeDashboardController as EmployeeController;
use App\Http\Controllers\EmployeeController\WebLeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TLController\LeadController;
use App\Http\Controllers\TLController\TaskController;
use App\Http\Controllers\TLController\TLEmployeeContoller;
use App\Http\Controllers\TLController\TLEmployeeController;
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
    Route::get('/leads', [WebLeadController::class, 'indexLeads'])->name('leads.index');
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
    Route::get('/dashboard', [TLDashboardController::class, 'dashboardStats'])->name('dashboard');
    Route::get('/team-lead/lead-report', [TLDashboardController::class, 'filterLeadReport'])->name('report');
    Route::get('/teams', [TLDashboardController::class, 'indexTeams'])->name('teams.index');
    Route::get('/leads', [LeadController::class, 'indexLeads'])->name('leads.index');
   //Route::post('/leads/{id}/forward', [LeadController::class, 'forwardToTeamLead'])->name('leads.forward');
    Route::post('/leads/{id}/authorize', [LeadController::class, 'authorizeLead'])->name('leads.authorize');
    //Route::post('/leads/{lead}/approve', [LeadController::class, 'authorizeLead'])->name('leads.approve');
    Route::post('/leads/{id}/reject', [LeadController::class, 'rejectLead'])->name('leads.reject');
    Route::post('/leads/{id}/future', [LeadController::class, 'markFutureLead'])->name('leads.future');
    //routes in which team lead can see the leads forwrded to him/her
    Route::get('/leads/forwarded-to-me', [LeadController::class, 'forwardedToMe'])->name('leads.forwarded_to_me');
    Route::post('/leads/{id}/forward-admin', [LeadController::class, 'forwardToAdmin']);
Route::post('/leads/{id}/forward-operations', [LeadController::class, 'forwardToOperations']);
Route::get('/operations-users', [LeadController::class, 'getOperationsUsers']);
Route::get('/leads/export', [LeadController::class, 'export'])->name('leads.export');



    Route::get('/tasks', [TLDashboardController::class, 'indexTasks'])->name('tasks.index');
    Route::get('/tasks/create', [TLDashboardController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [TLDashboardController::class, 'storeTask'])->name('tasks.store');

    Route::get('/setting', [TLDashboardController::class, 'indexSetting'])->name('setting.index');
    Route::post('/tasks/bulk-assign', [TLDashboardController::class, 'bulkAssignTasks'])->name('tasks.bulk_assign');
    Route::get('/notifications', [TLDashboardController::class, 'indexNotifications'])->name('notifications.index');

    // List all employees (for displaying in frontend if needed)
    //Route::get('/teams', [TLEmployeeController::class, 'index'])->name('employees.index');

    // Store new employee
    Route::post('/employees', [TLEmployeeController::class, 'store'])->name('employees.store');

    // Route::get('/team/employees/{id}/edit', [TLEmployeeController::class, 'edit'])->name('employees.edit');

    // Update existing employee
    Route::post('/employees/{id}', [TLEmployeeController::class, 'update'])->name('employees.update');

Route::post('/employees/{id}/deactivate', [TLEmployeeController::class, 'deactivate'])->name('employees.deactivate');
Route::post('/employees/{id}/activate', [TLEmployeeController::class, 'activate'])->name('employees.activate');


//report
Route::get('/export-report/{type}', [TeamLeadReportController::class, 'export'])
    ->name('teamlead.export');
    Route::get('/reports', [TeamLeadReportController::class, 'indexReports'])->name('reports.index');
    Route::get('/employee/details/{id}', [TeamLeadReportController::class, 'show']);




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

//Task management

Route::prefix('team-lead/tasks')->middleware('auth')->group(function () {
    Route::post('/store', [TaskController::class, 'store'])->name('team.tasks.store');
    //Route::get('/list', [TaskController::class, 'list'])->name('team.tasks.list');
   Route::get('/assigned-tasks', [TaskController::class, 'getAllTasksForTeamLead'])->name('teamlead.tasks');


});


require __DIR__.'/auth.php';

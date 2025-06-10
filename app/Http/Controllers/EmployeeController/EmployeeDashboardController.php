<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Attendance;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function dashboard()
    {
        $leads = Auth::user()->createdLeads()->latest()->take(5)->get();
        $tasks = Auth::user()->tasks()->latest()->take(5)->get();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return view('Employee.dashboard', compact('leads', 'tasks', 'notifications'));
    }

    // public function indexLeads()
    // {
    //     $leads = Auth::user()->createdLeads()->paginate(10);
    //     return view('Employee.leads.index', compact('leads'));
    // }

   

    // public function storeLead(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'email' => 'nullable|email|max:255',
    //         'dob' => 'nullable|date',
    //         'location' => 'required|string|max:255',
    //         'company_name' => 'nullable|string|max:255',
    //         'lead_amount' => 'required|numeric|min:0',
    //         'salary' => 'nullable|numeric|min:0',
    //         'success_percentage' => 'required|integer|min:0|max:100',
    //         'expected_month' => 'required|string|max:255',
    //         'remarks' => 'nullable|string',
    //     ]);

    //     $lead = Auth::user()->createdLeads()->create($validated + ['status' => 'pending']);

    //     // Notify Team Lead
    //     if ($teamLead = Auth::user()->teamLead) {
    //         $lead->update(['team_lead_id' => $teamLead->id]);
    //         $teamLead->notifications()->create([
    //             'lead_id' => $lead->id,
    //             'message' => "New lead created by {$lead->employee->name} awaits your review.",
    //         ]);
    //     }

    //     return redirect()->route('employee.leads.index')->with('success', 'Lead created successfully.');
    // }

    // public function editLead(Lead $lead)
    // {
    //     if ($lead->employee_id !== Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     return view('Employee.leads.edit', compact('lead'));
    // }

    // public function updateLead(Request $request, Lead $lead)
    // {
    //     if ($lead->employee_id !== Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'email' => 'nullable|email|max:255',
    //         'dob' => 'nullable|date',
    //         'location' => 'required|string|max:255',
    //         'company_name' => 'nullable|string|max:255',
    //         'lead_amount' => 'required|numeric|min:0',
    //         'salary' => 'nullable|numeric|min:0',
    //         'success_percentage' => 'required|integer|min:0|max:100',
    //         'expected_month' => 'required|string|max:255',
    //         'remarks' => 'nullable|string',
    //     ]);

    //     $lead->update($validated);

    //     return redirect()->route('employee.leads.index')->with('success', 'Lead updated successfully.');
    // }

    public function indexTasks()
    {
        $tasks = Auth::user()->tasks()->paginate(10);
        return view('Employee.task.index', compact('tasks'));
    }
    public function indexTeam()
    {
        $teamLead = Auth::user()->tasks()->paginate(10);
        return view('Employee.teams.index', compact('teamLead'));
    }
    public function indexSetting()
    {
         $user = Auth::user();
        return view('Employee.settings.index', compact('user'));
    }
  
    
    public function updateSetting(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('employee.settings.index')->with('status', 'profile-updated');
    }

    public function indexAttendance()
    {
        $attendances = Auth::user()->attendances()->latest()->paginate(10);
        return view('Employee.attendance.index', compact('attendances'));
    }

    // public function checkIn()
    // {
    //     $today = now()->toDateString();
    //     if (!Auth::user()->attendances()->where('date', $today)->exists()) {
    //         Attendance::create([
    //             'employee_id' => Auth::id(),
    //             'date' => $today,
    //             'check_in' => now(),
    //         ]);
    //         return redirect()->route('employee.attendance.index')->with('success', 'Checked in successfully.');
    //     }
    //     return redirect()->route('employee.attendance.index')->with('error', 'Already checked in today.');
    // }

    // public function checkOut()
    // {
    //     $attendance = Auth::user()->attendances()->where('date', now()->toDateString())->first();
    //     if ($attendance && !$attendance->check_out) {
    //         $attendance->update(['check_out' => now()]);
    //         return redirect()->route('employee.attendance.index')->with('success', 'Checked out successfully.');
    //     }
    //     return redirect()->route('employee.attendance.index')->with('error', 'No check-in found or already checked out.');
    // }

    // public function indexNotifications()
    // {
    //     $notifications = Auth::user()->notifications()->latest()->paginate(10);
    //     return view('Employee.notifications.index', compact('notifications'));
    // }
}
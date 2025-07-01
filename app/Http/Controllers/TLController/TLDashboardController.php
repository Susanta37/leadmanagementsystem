<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TLDashboardController extends Controller
{
    public function dashboard()
    {
        $employees = Auth::user()->employees()->latest()->take(5)->get();
        $leads = Auth::user()->assignedLeads()->latest()->take(5)->get();
        $tasks = Auth::user()->assignedTasks()->latest()->take(5)->get();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return view('TeamLead.dashboard', compact('employees', 'leads', 'tasks', 'notifications'));
    }

    // public function indexEmployees()
    // {
    //     $employees = Auth::user()->employees()->paginate(10);
    //     return view('team-lead.employees.index', compact('employees'));
    // }

    // public function createEmployee()
    // {
    //     return view('team-lead.employees.create');
    // }

    // public function storeEmployee(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:8|confirmed',
    //         'phone' => 'required|string|max:20',
    //         'department' => 'required|string|max:255',
    //         'profile_photo' => 'nullable|image|max:2048',
    //         'address' => 'nullable|string',
    //         'pan_card' => 'nullable|string|max:255',
    //         'aadhar_card' => 'nullable|string|max:255',
    //         'signature' => 'nullable|image|max:2048',
    //     ]);

    //     $data = $validated + [
    //         'designation' => 'employee',
    //         'created_by' => Auth::id(),
    //         'team_lead_id' => Auth::id(),
    //     ];

    //     if ($request->hasFile('profile_photo')) {
    //         $data['profile_photo'] = $request->file('profile_photo')->store('photos', 'public');
    //     }
    //     if ($request->hasFile('signature')) {
    //         $data['signature'] = $request->file('signature')->store('signatures', 'public');
    //     }

    //     User::create($data);

    //     return redirect()->route('team_lead.employees.index')->with('success', 'Employee created successfully.');
    // }



    public function indexTeams()
    {

  $employees = User::withTrashed() // 👈 this is the important part
        ->where('team_lead_id', auth()->id())
        ->get();

        return view('TeamLead.teams.index', compact('employees'));
    }
    public function indexReports()
    {

        return view('TeamLead.reports.index');
    }
    public function indexSetting(Request $request)
    {

        return view('TeamLead.settings.index' ,[
            'user' => $request->user(),
        ]);
    }

    // public function approveLead(Lead $lead)
    // {
    //     if ($lead->team_lead_id !== Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     $lead->update(['status' => 'approved']);
    //     $lead->employee->notifications()->create([
    //         'lead_id' => $lead->id,
    //         'message' => "Your lead '{$lead->name}' has been approved.",
    //     ]);
    //     // Notify Operations
    //     User::where('designation', 'operations')->get()->each(function ($user) use ($lead) {
    //         $user->notifications()->create([
    //             'lead_id' => $lead->id,
    //             'message' => "Lead '{$lead->name}' is ready for processing.",
    //         ]);
    //     });
    //     return redirect()->route('team_lead.leads.index')->with('success', 'Lead approved.');
    // }

    // public function rejectLead(Lead $lead)
    // {
    //     if ($lead->team_lead_id !== Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     $lead->update(['status' => 'rejected']);
    //     $lead->employee->notifications()->create([
    //         'lead_id' => $lead->id,
    //         'message' => "Your lead '{$lead->name}' has been rejected.",
    //     ]);
    //     return redirect()->route('team_lead.leads.index')->with('success', 'Lead rejected.');
    // }

    public function indexTasks()
    {
        $tasks = Auth::user()->assignedTasks()->paginate(10);
        return view('TeamLead.task.index', compact('tasks'));
    }

    // public function createTask()
    // {
    //     $employees = Auth::user()->employees()->get();
    //     return view('team-lead.tasks.create', compact('employees'));
    // }

    // public function storeTask(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'employee_id' => 'nullable|exists:users,id',
    //     ]);

    //     $task = Auth::user()->assignedTasks()->create($validated + ['status' => 'pending']);

    //     if ($task->employee_id) {
    //         $task->employee->notifications()->create([
    //             'task_id' => $task->id,
    //             'message' => "New task '{$task->title}' assigned to you.",
    //         ]);
    //     }

    //     return redirect()->route('team_lead.tasks.index')->with('success', 'Task created successfully.');
    // }

    // public function bulkAssignTasks(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'employee_ids' => 'required|array',
    //         'employee_ids.*' => 'exists:users,id',
    //     ]);

    //     foreach ($validated['employee_ids'] as $employeeId) {
    //         $task = Auth::user()->assignedTasks()->create([
    //             'title' => $validated['title'],
    //             'description' => $validated['description'],
    //             'employee_id' => $employeeId,
    //             'status' => 'pending',
    //         ]);
    //         User::find($employeeId)->notifications()->create([
    //             'task_id' => $task->id,
    //             'message' => "New task '{$task->title}' assigned to you.",
    //         ]);
    //     }

    //     return redirect()->route('team_lead.tasks.index')->with('success', 'Tasks assigned successfully.');
    // }

    // public function indexNotifications()
    // {
    //     $notifications = Auth::user()->notifications()->latest()->paginate(10);
    //     return view('team-lead.notifications.index', compact('notifications'));
    // }
}

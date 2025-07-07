<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lead;
use App\Models\LeadForwardedHistory;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TLDashboardController extends Controller
{
public function dashboardStats(Request $request)
{
    $teamLeadId = auth()->id();
    $search = $request->input('search');

    // Get all employee IDs under the team lead
    $employeeIds = User::where('designation', 'employee')
        ->where('team_lead_id', $teamLeadId)
        ->pluck('id');

    // Leads per employee
    $employeeLeadCounts = Lead::whereIn('employee_id', $employeeIds)
        ->selectRaw('employee_id, COUNT(*) as total')
        ->groupBy('employee_id')
        ->with('employee')
        ->get();

    $leadsPerEmployee = $employeeLeadCounts->map(function ($item) {
        return [
            'employee' => $item->employee->name ?? 'Unknown',
            'count' => $item->total,
        ];
    });

    // Leads per status
    $statusCounts = Lead::whereIn('employee_id', $employeeIds)
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // Lead filters
    $leadQuery = Lead::with(['employee', 'teamLead'])
        ->whereIn('employee_id', $employeeIds);

    if ($search) {
        $leadQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('company_name', 'like', "%$search%")
              ->orWhere('state', 'like', "%$search%")
              ->orWhere('district', 'like', "%$search%")
              ->orWhere('city', 'like', "%$search%");
        });
    }

    $leads = $leadQuery->latest()->paginate(10)->appends(['search' => $search]);

    // Team employee performance (not team lead)
    $teamPerformance = User::where('designation', 'employee')
        ->where('team_lead_id', $teamLeadId)
        ->get()
        ->map(function ($employee) {
            $leads = Lead::where('employee_id', $employee->id);
            $totalLeads = $leads->count();
            $avgSuccess = $leads->avg('success_percentage');

            return [
                'name' => $employee->name,
                'total_leads' => $totalLeads,
                'conversion_rate' => round($avgSuccess ?? 0, 1),
                'target_percentage' => min(100, round(($totalLeads / 50) * 100)),
            ];
        });

    // Dashboard card stats
    $stats = [
        'total_leads' => Lead::whereIn('employee_id', $employeeIds)->count(),
        'total_lead_value' => Lead::whereIn('employee_id', $employeeIds)->sum('lead_amount'),
        'authorized_leads' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'authorized')->count(),
        'authorized_lead_value' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'authorized')->sum('lead_amount'),
        'login_leads' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'login')->count(),
        'login_lead_value' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'login')->sum('lead_amount'),
        'approved_leads' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'approved')->count(),
        'approved_lead_value' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'approved')->sum('lead_amount'),
        'disbursed_leads' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'disbursed')->count(),
        'disbursed_lead_value' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'disbursed')->sum('lead_amount'),
        'rejected_leads' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'rejected')->count(),
        'rejected_lead_value' => Lead::whereIn('employee_id', $employeeIds)->where('status', 'rejected')->sum('lead_amount'),
        'active_employees' => User::where('designation', 'employee')
            ->where('team_lead_id', $teamLeadId)
            ->whereNull('deleted_at')
            ->count(),
    ];

    // Filter dropdown values
    $teamLeads = User::where('designation', 'team_lead')->get(['id', 'name']);
    $employees = User::where('designation', 'employee')->where('team_lead_id', $teamLeadId)->get(['id', 'name']);
    $statuses = Lead::select('status')->distinct()->pluck('status')->filter()->values();
    $companies = Lead::select('company_name')->distinct()->pluck('company_name')->filter()->values();
    $states = Lead::select('state')->distinct()->pluck('state')->filter()->values();
    $districts = Lead::select('district')->distinct()->pluck('district')->filter()->values();
    $cities = Lead::select('city')->distinct()->pluck('city')->filter()->values();
    $banks = Lead::select('bank_name')->distinct()->pluck('bank_name')->filter()->values();

    return view('TeamLead.dashboard', compact(
        'stats',
        'teamLeads',
        'employees',
        'statuses',
        'companies',
        'states',
        'districts',
        'cities',
        'banks',
        'leads',
        'leadsPerEmployee',
        'statusCounts',
        'teamPerformance',
        'search'
    ));
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

        // Count total and active employees
   $totalEmployees = User::withTrashed()
    ->where('team_lead_id', auth()->id())
    ->count();

$activeEmployees = User::where('team_lead_id', auth()->id())
    ->whereNull('deleted_at')
    ->count();




        return view('TeamLead.teams.index', compact('employees','totalEmployees','activeEmployees'));
    }

//       public function indexReports()
// {

//       $today = Carbon::today()->toDateString();

//     $attendances = Attendance::whereDate('date', $today)
//         ->select(
//             'id',
//             'employee_id',
//             'date',
//             'check_in',
//             'check_out',
//             'check_in_location',
//             'check_out_location',
//             'check_in_coordinates',
//             'check_out_coordinates',
//             'checkin_image',
//             'checkout_image',
//             'notes',
//             'is_within_geofence',
//             'created_at'
//         )
//         ->orderBy('check_in', 'asc')
//         ->get();

//     $stats = [
//         'total_leads' => Lead::count(),
//         'authorized_leads' => Lead::where('status', 'authorized')->count(),
//         'login_leads' => Lead::where('status', 'login')->count(),
//         'approved_leads' => Lead::where('status', 'approved')->count(),
//         'disbursed_leads' => Lead::where('status', 'disbursed')->count(),
//         'rejected_leads' => Lead::where('status', 'rejected')->count(),
//         'active_employees' => User::where('designation', 'employee')->whereNull('deleted_at')->count(), // If soft deletes used
//         'personal_leads' => Lead::where('status', 'personal_lead')->count(),
//     ];

//     return view('TeamLead.reports.index', compact('stats','attendances'));
// }

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
   $employees = User::where('designation', 'employee')->get();


    return view('TeamLead.task.index', compact('employees'));
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


public function filterLeadReport(Request $request)
{
    $query = Lead::query()->with(['employee', 'teamLead']);

    // 🧑‍💼 Only leads under this team lead
    $teamLeadId = auth()->id();
    $employeeIds = User::where('designation', 'employee')
        ->where('team_lead_id', $teamLeadId)
        ->pluck('id');
    $query->whereIn('employee_id', $employeeIds);

    // 🗓️ Date Range Filter
    if ($request->filled('date_range') && is_numeric($request->date_range)) {
        $query->whereBetween('created_at', [
            now()->subDays((int) $request->date_range)->startOfDay(),
            now()->endOfDay()
        ]);
    }

    // 📅 Custom Range
    if ($request->date_range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->start_date)->startOfDay(),
            Carbon::parse($request->end_date)->endOfDay(),
        ]);
    }

    // 🔍 Additional Filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('company')) {
        $query->where('company_name', $request->company);
    }

    if ($request->filled('state')) {
        $query->where('state', $request->state);
    }

    if ($request->filled('district')) {
        $query->where('district', $request->district);
    }

    if ($request->filled('city')) {
        $query->where('city', $request->city);
    }

    if ($request->filled('bank')) {
        $query->where('bank_name', $request->bank);
    }

    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    if ($request->filled('team_lead_id')) {
        $query->where('team_lead_id', $request->team_lead_id);
    }
    // 💰 Lead Amount Range
if ($request->filled('min_amount')) {
    $query->where('lead_amount', '>=', $request->min_amount);
}
if ($request->filled('max_amount')) {
    $query->where('lead_amount', '<=', $request->max_amount);
}


    // 💾 Fetch leads (paginated for leads table)
    $leads = $query->latest()->paginate(10)->appends($request->all());

    // 📊 Leads per employee (chart)
    $leadsPerEmployee = $query->get()->groupBy('employee_id')->map(function ($group, $empId) {
        return [
            'employee' => $group->first()->employee?->name ?? 'Unknown',
            'count' => $group->count()
        ];
    })->values();

    // 📊 Status Distribution (chart)
    $statusCounts = $query->get()->groupBy('status')->map(fn($group) => $group->count());

    // 👥 Team Performance (cards)
    $teamPerformance = User::where('designation', 'employee')
        ->where('team_lead_id', $teamLeadId)
        ->get()
        ->map(function ($employee) use ($query) {
            $employeeLeads = (clone $query)->where('employee_id', $employee->id)->get();
            $total = $employeeLeads->count();
            $avgSuccess = $employeeLeads->avg('success_percentage');
            return [
                'name' => $employee->name,
                'total_leads' => $total,
                'conversion_rate' => round($avgSuccess ?? 0, 1),
                'target_percentage' => min(100, round(($total / 50) * 100))
            ];
        });

    // 📦 Dashboard Stats
    $stats = [
        'total_leads' => $query->count(),
        'total_lead_value' => $query->sum('lead_amount'),
        'authorized_leads' => (clone $query)->where('status', 'authorized')->count(),
        'authorized_lead_value' => (clone $query)->where('status', 'authorized')->sum('lead_amount'),
        'login_leads' => (clone $query)->where('status', 'login')->count(),
        'login_lead_value' => (clone $query)->where('status', 'login')->sum('lead_amount'),
        'approved_leads' => (clone $query)->where('status', 'approved')->count(),
        'approved_lead_value' => (clone $query)->where('status', 'approved')->sum('lead_amount'),
        'disbursed_leads' => (clone $query)->where('status', 'disbursed')->count(),
        'disbursed_lead_value' => (clone $query)->where('status', 'disbursed')->sum('lead_amount'),
        'rejected_leads' => (clone $query)->where('status', 'rejected')->count(),
        'rejected_lead_value' => (clone $query)->where('status', 'rejected')->sum('lead_amount'),
        'active_employees' => User::where('designation', 'employee')
            ->where('team_lead_id', $teamLeadId)
            ->whereNull('deleted_at')
            ->count(),
    ];

    // 🔁 Dropdown data
    $teamLeads = User::where('designation', 'team_lead')->get(['id', 'name']);
    $employees = User::where('designation', 'employee')->where('team_lead_id', $teamLeadId)->get(['id', 'name']);
    $statuses = Lead::select('status')->distinct()->pluck('status')->filter()->values();
    $companies = Lead::select('company_name')->distinct()->pluck('company_name')->filter()->values();
    $states = Lead::select('state')->distinct()->pluck('state')->filter()->values();
    $districts = Lead::select('district')->distinct()->pluck('district')->filter()->values();
    $cities = Lead::select('city')->distinct()->pluck('city')->filter()->values();
    $banks = Lead::select('bank_name')->distinct()->pluck('bank_name')->filter()->values();

    return view('TeamLead.dashboard', compact(
        'stats',
        'teamLeads',
        'employees',
        'statuses',
        'companies',
        'states',
        'districts',
        'cities',
        'banks',
        'leads',
        'leadsPerEmployee',
        'statusCounts',
        'teamPerformance'
    ));
}

}

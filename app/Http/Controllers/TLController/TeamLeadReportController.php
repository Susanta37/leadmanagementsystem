<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeamLeadReportController extends Controller
{
//     public function export($type)
// {
//     return Excel::download(new LeadExport($type), ucfirst($type) . '_Leads.xlsx');
// }

public function indexReports(Request $request)
{
    $today = Carbon::today();

    // ✅ Attendance - Only for today's check-in
    $attendances = Attendance::whereDate('check_in', $today)->get();

    // ✅ Stats for dashboard cards
    $stats = [
        'total_leads' => Lead::count(),
        'authorized_leads' => Lead::where('status', 'authorized')->count(),
        'login_leads' => Lead::where('status', 'login')->count(),
        'approved_leads' => Lead::where('status', 'approved')->count(),
        'disbursed_leads' => Lead::where('status', 'disbursed')->count(),
        'rejected_leads' => Lead::where('status', 'rejected')->count(),
        'active_employees' => User::where('designation', 'employee')->whereNull('deleted_at')->count(),
        'personal_leads' => Lead::where('status', 'personal_lead')->count(),
    ];

    // ✅ Lead Filtering Logic
    $query = Lead::query();
    $filter = $request->input('filter');
    $from = $request->input('from');
    $to = $request->input('to');

    if ($filter && $filter !== 'custom') {
        $days = (int)$filter;
        $fromDate = Carbon::now()->subDays($days)->startOfDay();
        $query->whereDate('created_at', '>=', $fromDate);
    }

    if ($filter === 'custom' && $from && $to) {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();
        $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    $leads = $query->with('employee')->latest()->get();

    $tasks = Task::with(['notifications' => function ($query) {
        $query->select('id', 'user_id', 'task_id');
    }])->get();

    // ✅ Add employees data for overview section
    $employees = User::where('designation', 'employee')->whereNull('deleted_at')->get();

    return view('TeamLead.reports.index', compact('stats', 'attendances', 'leads', 'tasks', 'employees'));
}


public function show($id)
{
    $user = User::withCount(['leads as leads_completed' => function ($query) {
        $query->where('status', 'disbursed');
    }])->with('teamLead')->findOrFail($id);

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'designation' => $user->designation,
        'department' => $user->department,
        'address' => $user->address,
        'created_at' => $user->created_at,
        'profile_photo' => $user->profile_photo,
        'leads_completed' => $user->leads_completed,
        'performance_rate' => 92, // optional logic
        'revenue' => '₹25L', // optional logic
        'team_lead_name' => $user->teamLead->name ?? null,
    ]);
}


}

<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use App\Models\LeadForwardedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLeadAssigned;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{


// public function indexLeads(Request $request)
// {
//     $user = Auth::user();
//     $query = Lead::with(['employee', 'teamLead'])
//         ->whereNull('deleted_at');

//     // 🔹 Show leads forwarded to the logged-in team lead
//     $forwardedLeadIds = LeadForwardedHistory::where('receiver_user_id', $user->id)
//         ->where('is_forwarded', 1)
//         ->pluck('lead_id')
//         ->toArray();

//     // 🔹 Leads either assigned to the team lead or forwarded to them
//     $query->where(function ($q) use ($user, $forwardedLeadIds) {
//         $q->where('team_lead_id', $user->id)
//           ->orWhereIn('id', $forwardedLeadIds);
//     });

//     // Default to current month's leads
//     // $query->whereMonth('created_at', Carbon::now()->month)
//     //       ->whereYear('created_at', Carbon::now()->year);

//     // ... (keep rest of your filter logic as is)

//     // Final paginate
//     $leads = $query->orderBy('created_at', 'desc')->paginate(10);

//     // Format leads
//    $formattedLeads = $leads->getCollection()->map(function ($lead) {
//     return [
//         'id' => $lead->id,
//         'name' => $lead->name,
//         'email' => $lead->email ?? '-',
//         'phone' => $lead->phone,
//         'location' => $lead->city ? "{$lead->city}, {$lead->state}" : ($lead->state ?? '-'),
//         'company' => $lead->company_name ?? '-',
//         'position' => $lead->position ?? '-',
//         'industry' => $lead->lead_type ?? '-',
//         'website' => $lead->website ?? '-',
//         'amount' => (int) $lead->lead_amount,
//         'status' => $lead->status,
//         'source' => $lead->lead_source ?? '-',
//         'expected_date' => $lead->expected_month ? Carbon::parse($lead->expected_month)->format('M d, Y') : '-',
//         'notes' => $lead->remarks ?? '-',
//         'created_at' => $lead->created_at->format('d M Y'),
//         'assigned' => $lead->team_lead_id !== null,
//         'team_lead_assigned' => $lead->team_lead_id ? true : false,
//         'employee_name' => $lead->employee ? $lead->employee->name : '-',
//         'team_lead_name' => $lead->teamLead ? $lead->teamLead->name : '-'
//     ];
// });

//    Log::info('Formatted Leads Count: ' . $formattedLeads->count());

//     // Pass data to the view
//     return view('TeamLead.leads.index', [
//         'leads' => $leads,
//         'formattedLeads' => $formattedLeads
//     ]);
// }


    // public function forwardToTeamLead(Request $request, Lead $lead)
    // {
    //     $user = Auth::user();

    //     // Authorization: Only the employee who created the lead can forward it
    //     if ($lead->employee_id !== $user->id || !$lead->is_personal_lead || $lead->status !== 'personal_lead') {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Unauthorized to forward this lead or lead is not a personal lead.'
    //         ], 403);
    //     }

    //     // Validate request
    //     $request->validate([
    //         'team_lead_id' => 'required|exists:users,id',
    //         'remarks' => 'nullable|string|max:1000'
    //     ]);

    //     // Update lead
    //     $lead->update([
    //         'team_lead_id' => $request->team_lead_id,
    //         'remarks' => $request->remarks ?? $lead->remarks,
    //         'is_personal_lead' => false
    //     ]);

    //     // Log history
    //     $lead->histories()->create([
    //         'user_id' => $user->id,
    //         'action' => 'forwarded_to_team_lead',
    //         'remarks' => $request->remarks ?? 'Lead forwarded to team lead by employee.'
    //     ]);

    //     // Notify team lead
    //     $teamLead = User::find($request->team_lead_id);
    //     if ($teamLead) {
    //         Notification::send($teamLead, new NewLeadAssigned($lead, $user));
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Lead forwarded to team lead successfully.'
    //     ]);
    // }




public function indexLeads(Request $request)
{
    $user = Auth::user();

    // 🔹 Get only lead IDs that are actively forwarded to this team lead
    $forwardedLeadIds = LeadForwardedHistory::where('receiver_user_id', $user->id)
        ->where('is_forwarded', true)
        ->pluck('lead_id')
        ->toArray();

    // 🔹 Main query: Only forwarded leads
    $query = Lead::with(['employee', 'teamLead'])
        ->whereIn('id', $forwardedLeadIds)
        ->whereNull('deleted_at');

    // 🔹 Filter: Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔹 Filter: Assignment
    if ($request->filled('assignment')) {
        if ($request->assignment === 'assigned') {
            $query->whereNotNull('team_lead_id');
        } elseif ($request->assignment === 'unassigned') {
            $query->whereNull('team_lead_id');
        }
    }

    // 🔹 Filter: Date Range
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // 🔹 Filter: Search (by name or company)
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('company_name', 'like', "%{$searchTerm}%");
        });
    }

    // 🔹 Pagination and formatting
    $leads = $query->orderBy('created_at', 'desc')->paginate(10);

    $formattedLeads = $leads->getCollection()->map(function ($lead) {
        return [
            'id' => $lead->id,
            'name' => $lead->name,
            'email' => $lead->email ?? '-',
            'phone' => $lead->phone,
            'location' => $lead->city ? "{$lead->city}, {$lead->state}" : ($lead->state ?? '-'),
            'company' => $lead->company_name ?? '-',
            'position' => $lead->position ?? '-',
            'industry' => $lead->lead_type ?? '-',
            'website' => $lead->website ?? '-',
            'amount' => (int) $lead->lead_amount,
            'status' => $lead->status,
            'source' => $lead->lead_source ?? '-',
            'expected_date' => $lead->expected_month ? Carbon::parse($lead->expected_month)->format('M d, Y') : '-',
            'notes' => $lead->remarks ?? '-',
            'created_at' => $lead->created_at->format('d M Y'),
            'assigned' => $lead->team_lead_id !== null,
            'team_lead_assigned' => $lead->team_lead_id ? true : false,
            'employee_name' => $lead->employee ? $lead->employee->name : '-',
            'team_lead_name' => $lead->teamLead ? $lead->teamLead->name : '-'
        ];
    });

    return view('TeamLead.leads.index', [
        'leads' => $leads,
        'formattedLeads' => $formattedLeads
    ]);
}



    public function authorizeLead($id, Request $request)
{
    $lead = Lead::findOrFail($id);
    $lead->status = 'authorized';
     $lead->remarks = $request->input('remarks');
    $lead->save();

    return response()->json(['status' => 'success', 'message' => 'Lead authorized successfully.']);
}

   public function markFutureLead($id, Request $request)
{
    $lead = Lead::findOrFail($id);
    $lead->status = 'future_lead';
    $lead->expected_month = $request->input('expected_month') ?? null;
    $lead->save();

    return response()->json(['status' => 'success', 'message' => 'Lead marked as future lead.']);
}

    // public function approveLead(Request $request, Lead $lead)
    // {
    //     $user = Auth::user();

    //     // Authorization: Only the assigned team lead can approve
    //     if ($lead->team_lead_id !== $user->id) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Unauthorized to approve this lead.'
    //         ], 403);
    //     }

    //     // Validate request
    //     $request->validate([
    //         'remarks' => 'nullable|string|max:1000',
    //         'forward_to_operations' => 'nullable|boolean'
    //     ]);

    //     $status = $request->forward_to_operations ? 'login' : 'approved';

    //     $lead->update([
    //         'status' => $status,
    //         'remarks' => $request->remarks ?? $lead->remarks
    //     ]);

    //     // Log history
    //     $lead->histories()->create([
    //         'user_id' => $user->id,
    //         'action' => $status === 'login' ? 'approved_and_forwarded' : 'approved',
    //         'remarks' => $request->remarks ?? ($status === 'login' ? 'Lead approved and forwarded to operations.' : 'Lead approved by team lead.')
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $status === 'login' ? 'Lead approved and forwarded to operations successfully.' : 'Lead approved successfully.'
    //     ]);
    // }



    public function rejectLead($id, Request $request)
{
    $lead = Lead::findOrFail($id);
    $lead->status = 'rejected';
     $lead->reason = $request->input('remarks');
    $lead->save();

    return response()->json(['status' => 'success', 'message' => 'Lead rejected successfully.']);
}





    public function forwardedToMe(Request $request)
{
    $userId = Auth::id();

    $forwardedLeadIds = LeadForwardedHistory::where('receiver_user_id', $userId)
        ->where('is_forwarded', 1)
        ->pluck('lead_id');

    $leads = Lead::whereIn('id', $forwardedLeadIds)
        ->with(['employee', 'teamLead'])
        ->whereNull('deleted_at')
        ->latest()
        ->paginate(10);

    $formattedLeads = $leads->map(function ($lead) {
        return [
            'id' => $lead->id,
            'name' => $lead->name,
            'email' => $lead->email ?? '-',
            'phone' => $lead->phone,
            'location' => $lead->city ? "{$lead->city}, {$lead->state}" : ($lead->state ?? '-'),
            'company' => $lead->company_name ?? '-',
            'position' => $lead->position ?? '-',
            'industry' => $lead->lead_type ?? '-',
            'website' => $lead->website ?? '-',
            'amount' => number_format($lead->lead_amount, 0, '.', ','),
            'status' => $lead->status,
            'source' => $lead->lead_source ?? '-',
            'expected_date' => $lead->expected_month ? Carbon::parse($lead->expected_month)->format('M d, Y') : '-',
            'notes' => $lead->remarks ?? '-',
            'created_at' => $lead->created_at->format('d M Y'),
            'assigned' => $lead->team_lead_id !== null,
            'team_lead_assigned' => $lead->team_lead_id ? true : false,
            'employee_name' => $lead->employee ? $lead->employee->name : '-',
            'team_lead_name' => $lead->teamLead ? $lead->teamLead->name : '-'
        ];
    });

    return view('TeamLead.leads.forwarded_to_me', [
        'leads' => $leads,
        'formattedLeads' => $formattedLeads
    ]);
}
public function forwardToAdmin(Request $request, $id)
{
    $lead = Lead::findOrFail($id);
    $admin = User::where('designation', 'admin')->first();

    if (!$admin) {
        return response()->json(['status' => 'error', 'message' => 'No admin found.']);
    }

    // ✅ Step 1: Set all previous forwards of this lead to false
    LeadForwardedHistory::where('lead_id', $lead->id)
        ->where('is_forwarded', true)
        ->update(['is_forwarded' => false]);

    // ✅ Step 2: Save remarks to lead
    $lead->remarks = $request->remarks;
    $lead->save();

    // ✅ Step 3: Create new forwarded row
    LeadForwardedHistory::create([
        'lead_id' => $lead->id,
        'sender_user_id' => Auth::id(),
        'receiver_user_id' => $admin->id,
        'is_forwarded' => true,
        'forwarded_at' => now()
    ]);

    return response()->json(['status' => 'success']);
}






public function getOperationsUsers()
{
    $users = User::where('designation', 'operations')
        ->select('id', 'name')
        ->get();

    return response()->json($users);
}


public function forwardToOperations(Request $request, $leadId)
{
    $teamLead = Auth::user();

    $request->validate([
        'receiver_user_id' => 'required|exists:users,id'
    ]);

    // ✅ Step 1: Mark all previous active forwards of this lead as not forwarded
    LeadForwardedHistory::where('lead_id', $leadId)
        ->where('is_forwarded', true)
        ->update(['is_forwarded' => false]);

         $lead = Lead::findOrFail($leadId);
    $lead->status = 'authorized';
      $lead->remarks = $request->remarks;
    $lead->save();

    // ✅ Step 2: Create new forwarding entry
    LeadForwardedHistory::create([
        'lead_id' => $leadId,
        'sender_user_id' => $teamLead->id,
        'receiver_user_id' => $request->receiver_user_id,
        'is_forwarded' => true,
        'forwarded_at' => now()
    ]);

    return response()->json(['status' => 'success', 'message' => 'Lead forwarded to operation team']);
}

public function export()
{
    $leads = Lead::with('teamLead')->whereNull('deleted_at')->get();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=leads_report.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $columns = ['Name', 'Company', 'Location', 'Amount', 'Success %', 'Expected Month', 'Status', 'Team Lead'];

    $callback = function () use ($leads, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($leads as $lead) {
            fputcsv($file, [
                $lead->client_name,
                $lead->company,
                $lead->city ?? $lead->district ?? $lead->state ?? '',
                $lead->lead_amount,
                $lead->success_percentage,
                Carbon::parse($lead->expected_month)->format('F Y'),
                ucfirst($lead->status),
                $lead->teamLead->name ?? '',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


}

<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLeadAssigned;

class LeadController extends Controller
{
    public function indexLeads(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with(['employee', 'teamLead'])
            ->where('team_lead_id', $user->id)
            ->whereNull('deleted_at');

        // Default to current month's leads
        $query->whereMonth('created_at', Carbon::now()->month)
              ->whereYear('created_at', Carbon::now()->year);

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('assignment') && $request->assignment !== '') {
            if ($request->assignment === 'assigned') {
                $query->whereNotNull('team_lead_id');
            } elseif ($request->assignment === 'unassigned') {
                $query->whereNull('team_lead_id');
            }
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Order and paginate
        $leads = $query->orderBy('created_at', 'desc')->paginate(10);

        // Format leads for the view
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

        // Pass data to the view
        return view('TeamLead.leads.index', [
            'leads' => $leads,
            'formattedLeads' => $formattedLeads
        ]);
    }

    public function forwardToTeamLead(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Authorization: Only the employee who created the lead can forward it
        if ($lead->employee_id !== $user->id || !$lead->is_personal_lead || $lead->status !== 'personal_lead') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to forward this lead or lead is not a personal lead.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'team_lead_id' => 'required|exists:users,id',
            'remarks' => 'nullable|string|max:1000'
        ]);

        // Update lead
        $lead->update([
            'team_lead_id' => $request->team_lead_id,
            'remarks' => $request->remarks ?? $lead->remarks,
            'is_personal_lead' => false
        ]);

        // Log history
        $lead->histories()->create([
            'user_id' => $user->id,
            'action' => 'forwarded_to_team_lead',
            'remarks' => $request->remarks ?? 'Lead forwarded to team lead by employee.'
        ]);

        // Notify team lead
        $teamLead = User::find($request->team_lead_id);
        if ($teamLead) {
            Notification::send($teamLead, new NewLeadAssigned($lead, $user));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lead forwarded to team lead successfully.'
        ]);
    }

    public function setAuthorized(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Authorization: Only the assigned team lead can authorize
        if ($lead->team_lead_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this lead.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'remarks' => 'nullable|string|max:1000'
        ]);

        $lead->update([
            'status' => 'authorized',
            'remarks' => $request->remarks ?? $lead->remarks
        ]);

        // Log history
        $lead->histories()->create([
            'user_id' => $user->id,
            'action' => 'authorized',
            'remarks' => $request->remarks ?? 'Lead authorized by team lead.'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead authorized successfully.'
        ]);
    }

    public function setFutureLead(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Authorization: Only the assigned team lead can update
        if ($lead->team_lead_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this lead.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'expected_month' => 'nullable|date_format:Y-m'
        ]);

        $lead->update([
            'status' => 'future_lead',
            'remarks' => $request->remarks ?? $lead->remarks,
            'expected_month' => $request->expected_month ?? $lead->expected_month
        ]);

        // Log history
        $lead->histories()->create([
            'user_id' => $user->id,
            'action' => 'set_future_lead',
            'remarks' => $request->remarks ?? 'Lead marked as future lead by team lead.'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead marked as future lead successfully.'
        ]);
    }

    public function approveLead(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Authorization: Only the assigned team lead can approve
        if ($lead->team_lead_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to approve this lead.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'forward_to_operations' => 'nullable|boolean'
        ]);

        $status = $request->forward_to_operations ? 'login' : 'approved';

        $lead->update([
            'status' => $status,
            'remarks' => $request->remarks ?? $lead->remarks
        ]);

        // Log history
        $lead->histories()->create([
            'user_id' => $user->id,
            'action' => $status === 'login' ? 'approved_and_forwarded' : 'approved',
            'remarks' => $request->remarks ?? ($status === 'login' ? 'Lead approved and forwarded to operations.' : 'Lead approved by team lead.')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $status === 'login' ? 'Lead approved and forwarded to operations successfully.' : 'Lead approved successfully.'
        ]);
    }

    public function rejectLead(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Authorization: Only the assigned team lead can reject
        if ($lead->team_lead_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to reject this lead.'
            ], 403);
        }

        // Validate request
        $request->validate([
            'remarks' => 'nullable|string|max:1000'
        ]);

        $lead->update([
            'status' => 'rejected',
            'remarks' => $request->remarks ?? $lead->remarks
        ]);

        // Log history
        $lead->histories()->create([
            'user_id' => $user->id,
            'action' => 'rejected',
            'remarks' => $request->remarks ?? 'Lead rejected by team lead.'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead rejected successfully.'
        ]);
    }
}
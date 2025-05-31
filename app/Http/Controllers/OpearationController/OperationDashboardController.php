<?php

namespace App\Http\Controllers\OpearationController;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationDashboardController extends Controller
{
    public function dashboard()
    {
        $leads = Lead::where('status', 'approved')->latest()->take(5)->get();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return view('Opearation.dashboard', compact('leads', 'notifications'));
    }

    // public function indexLeads()
    // {
    //     $leads = Lead::where('status', 'approved')->paginate(10);
    //     return view('operations.leads.index', compact('leads'));
    // }

    // public function completeLead(Lead $lead)
    // {
    //     if ($lead->status !== 'approved') {
    //         abort(403, 'Only approved leads can be completed.');
    //     }
    //     $lead->update(['status' => 'completed']);
    //     $lead->employee->notifications()->create([
    //         'lead_id' => $lead->id,
    //         'message' => "Your lead '{$lead->name}' has been completed.",
    //     ]);
    //     if ($lead->teamLead) {
    //         $lead->teamLead->notifications()->create([
    //             'lead_id' => $lead->id,
    //             'message' => "Lead '{$lead->name}' has been completed.",
    //         ]);
    //     }
    //     return redirect()->route('operations.leads.index')->with('success', 'Lead completed.');
    // }

    // public function indexNotifications()
    // {
    //     $notifications = Auth::user()->notifications()->latest()->paginate(10);
    //     return view('operations.notifications.index', compact('notifications'));
    // }
}
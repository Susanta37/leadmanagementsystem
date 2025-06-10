<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class WebLeadController extends Controller
{
       // public function __construct()
    // {
    //     $this->middleware('auth'); // Ensure user is authenticated
    // }

    /**
     * Display a listing of leads.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexLeads(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $status = $request->query('status', '');
        $assignment = $request->query('assignment', '');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $search = $request->query('search');

        // Validate filter parameters
        $validStatuses = ['', 'pending', 'approved', 'rejected', 'completed'];
        $validAssignments = ['', 'assigned', 'unassigned'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Invalid status filter');
        }

        if (!in_array($assignment, $validAssignments)) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Invalid assignment filter');
        }

        if ($dateFrom && !Carbon::hasFormat($dateFrom, 'Y-m-d')) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Invalid date from format');
        }

        if ($dateTo && !Carbon::hasFormat($dateTo, 'Y-m-d')) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Invalid date to format');
        }

        if ($dateFrom && $dateTo && Carbon::parse($dateFrom)->gt(Carbon::parse($dateTo))) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Date from must be before date to');
        }

        // Build query
        $query = $user->createdLeads()->with(['teamLead']);

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Apply assignment filter
        if ($assignment === 'assigned') {
            $query->whereNotNull('team_lead_id');
        } elseif ($assignment === 'unassigned') {
            $query->whereNull('team_lead_id');
        }

        // Apply date range filter
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Fetch leads with pagination
        $leads = $query->paginate(10);

        // Fetch team leads for the form
        $teamLeads = User::where('designation', 'team_lead')->get(['id', 'name']);

        return view('Employee.leads.index', compact(
            'leads',
            'teamLeads',
            'status',
            'assignment',
            'dateFrom',
            'dateTo',
            'search'
        ));
    }

    /**
     * Store a newly created lead.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'location' => 'required|string|max:255',
            'lead_amount' => 'required|numeric|min:0',
            'expected_month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'email' => 'nullable|string|email|max:255',
            'dob' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'success_percentage' => 'nullable|integer|min:0|max:100',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:pending,approved,rejected,completed',
            'team_lead_id' => 'nullable|exists:users,id',
            'lead_type' => 'nullable|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('employee.leads.index')
                ->withErrors($validator)
                ->withInput();
        }

        $voiceRecordingPath = null;
        if ($request->hasFile('voice_recording')) {
            $voiceRecordingPath = $request->file('voice_recording')->store('voice_recordings', 'public');
        }
        $finalVoiceRecordingPath = $voiceRecordingPath ? '/storage/' . $voiceRecordingPath : null;

        Lead::create([
            'employee_id' => Auth::id(),
            'team_lead_id' => $request->team_lead_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'dob' => $request->dob,
            'location' => $request->location,
            'company_name' => $request->company_name,
            'lead_amount' => $request->lead_amount,
            'salary' => $request->salary,
            'success_percentage' => $request->success_percentage,
            'expected_month' => $request->expected_month,
            'remarks' => $request->remarks,
            'status' => $request->status ?? 'pending',
            'lead_type' => $request->lead_type,
            'voice_recording' => $finalVoiceRecordingPath,
        ]);

        return redirect()->route('employee.leads.index')
            ->with('success', 'Lead created successfully');
    }

    /**
     * Show the form for editing a lead.
     *
     * @param Lead $lead
     * @return \Illuminate\View\View
     */
    public function edit(Lead $lead)
    {
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Unauthorized to edit this lead');
        }

        $lead->load(['employee', 'teamLead']);
        return view('Employee.leads.index', compact('lead'));
    }

    /**
     * Update an existing lead.
     *
     * @param Request $request
     * @param Lead $lead
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Lead $lead)
    {
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Unauthorized to update this lead');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'email' => 'sometimes|string|email|max:255',
            'dob' => 'nullable|date',
            'location' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'lead_amount' => 'sometimes|numeric|min:0',
            'salary' => 'nullable|numeric|min:0',
            'success_percentage' => 'sometimes|integer|min:0|max:100',
            'expected_month' => 'nullable|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'remarks' => 'nullable|string',
            'status' => 'sometimes|string|in:pending,approved,rejected,completed',
            'team_lead_id' => 'sometimes|exists:users,id',
            'lead_type' => 'required|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('employee.leads.index')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'name',
            'phone',
            'email',
            'dob',
            'location',
            'company_name',
            'lead_amount',
            'salary',
            'success_percentage',
            'expected_month',
            'remarks',
            'status',
            'team_lead_id',
            'lead_type',
        ]);

        if ($request->hasFile('voice_recording')) {
            if ($lead->voice_recording) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $lead->voice_recording));
            }
            $data['voice_recording'] = '/storage/' . $request->file('voice_recording')->store('voice_recordings', 'public');
        }

        $lead->update($data);

        return redirect()->route('employee.leads.index')
            ->with('success', 'Lead updated successfully');
    }

    /**
     * Delete a lead.
     *
     * @param Lead $lead
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Lead $lead)
    {
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Unauthorized to delete this lead');
        }

        $lead->delete();

        return redirect()->route('employee.leads.index')
            ->with('success', 'Lead deleted successfully');
    }

    /**
     * Forward a lead to a team lead.
     *
     * @param Request $request
     * @param Lead $lead
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forwardToTeamLead(Request $request, Lead $lead)
    {
        if (Auth::id() !== $lead->employee_id) {
            return redirect()->route('employee.leads.index')
                ->with('error', 'Unauthorized to forward this lead');
        }

        $validator = Validator::make($request->all(), [
            'team_lead_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('employee.leads.index')
                ->withErrors($validator)
                ->withInput();
        }

        if ($lead->team_lead_id) {
            return redirect()->route('employee.leads.index')
                ->with('info', 'Lead is already assigned to a team lead');
        }

        $lead->update(['team_lead_id' => $request->team_lead_id]);

        return redirect()->route('employee.leads.index')
            ->with('success', 'Lead forwarded to team lead successfully');
    }
}
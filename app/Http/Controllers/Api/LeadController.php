<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    /**
     * Get a list of all leads.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Get filter parameters
        $leadType = $request->query('lead_type', 'all');
        $status = $request->query('status', 'all');
        $dateFilter = $request->query('date_filter', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Validate filter parameters
        $validLeadTypes = ['all', 'personal_loan', 'home_loan', 'business_loan', 'creditcard_loan'];
        $validStatuses = ['all', 'pending', 'approved', 'completed', 'rejected'];
        $validDateFilters = ['this_month', 'this_week', 'this_year', 'date_range'];
        
        if (!in_array($leadType, $validLeadTypes)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid lead type',
            ], 400);
        }
        
        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid status',
            ], 400);
        }
        
        if (!in_array($dateFilter, $validDateFilters)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date filter',
            ], 400);
        }
        
        // Validate date range if provided
        if ($dateFilter === 'date_range') {
            if (!$startDate || !$endDate) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Start date and end date are required for date range filter',
                ], 400);
            }
            
            try {
                $startDate = Carbon::parse($startDate);
                $endDate = Carbon::parse($endDate);
                if ($startDate > $endDate) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Start date must be before end date',
                    ], 400);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid date format',
                ], 400);
            }
        }

        // Base query based on user role
        $query = Lead::query();
        if ($user->designation !== 'team_lead') {
            $query->where('employee_id', $user->id);
        }

        // Apply date filter
        $now = Carbon::now();
        if ($dateFilter === 'this_month') {
            $query->whereYear('created_at', $now->year)
                  ->whereMonth('created_at', $now->month);
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        } elseif ($dateFilter === 'this_year') {
            $query->whereYear('created_at', $now->year);
        } elseif ($dateFilter === 'date_range') {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        // Apply lead type filter
        if ($leadType !== 'all') {
            $query->where('lead_type', $leadType);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get all leads with relationships
        $leads = $query->with(['employee', 'teamLead'])->get();

        // Aggregate data
        $totalLeads = [
            'count' => $query->count(),
            'total_amount' => $query->sum('lead_amount'),
        ];

        $approvedLeads = [
            'count' => $query->clone()->where('status', 'approved')->count(),
            'total_amount' => $query->clone()->where('status', 'approved')->sum('lead_amount'),
        ];

        $disbursedLeads = [
            'count' => $query->clone()->where('status', 'completed')->count(),
            'total_amount' => $query->clone()->where('status', 'completed')->sum('lead_amount'),
        ];

        $pendingLeads = [
            'count' => $query->clone()->where('status', 'pending')->count(),
            'total_amount' => $query->clone()->where('status', 'pending')->sum('lead_amount'),
        ];

        $rejectedLeads = [
            'count' => $query->clone()->where('status', 'rejected')->count(),
            'total_amount' => $query->clone()->where('status', 'rejected')->sum('lead_amount'),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Leads retrieved successfully',
            'data' => [
                'leads' => $leads,
                'aggregates' => [
                    'total_leads' => $totalLeads,
                    'approved_leads' => $approvedLeads,
                    'disbursed_leads' => $disbursedLeads,
                    'pending_leads' => $pendingLeads,
                    'rejected_leads' => $rejectedLeads,
                ],
                'filters_applied' => [
                    'lead_type' => $leadType,
                    'status' => $status,
                    'date_filter' => $dateFilter,
                    'start_date' => $startDate ?? null,
                    'end_date' => $endDate ?? null,
                ],
            ],
        ], 200);
    }

    /**
     * Create a new lead.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
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
            'is_personal_lead' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $voice_recording_path = null;
        if ($request->hasFile('voice_recording')) {
            $voice_recording_path = $request->file('voice_recording')->store('voice_recordings', 'public');
        }
        $final_voice_recording_path = $voice_recording_path ? '/storage/' . $voice_recording_path : null;

        $lead = Lead::create([
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
            'voice_recording' => $final_voice_recording_path,
            'is_personal_lead' => $request->is_personal_lead ?? true,
        ]);

        $lead->load(['employee', 'teamLead']);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead created successfully',
            'data' => $lead,
        ], 201);
    }

    /**
     * Get a specific lead by ID.
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['employee', 'teamLead']);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead retrieved successfully',
            'data' => $lead,
        ], 200);
    }

    /**
     * Get lead data for editing.
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function edit(Lead $lead): JsonResponse
    {
        // Restrict access to the lead's employee or team lead
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to edit this lead',
            ], 403);
        }

        // Check if lead is editable (must be personal lead)
        if (!$lead->is_personal_lead) {
            return response()->json([
                'status' => 'error',
                'message' => 'This lead is not editable',
            ], 403);
        }

        // Load relationships
        $lead->load(['employee', 'teamLead']);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead data retrieved for editing',
            'data' => [
                'lead' => [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'phone' => $lead->phone,
                    'email' => $lead->email,
                    'dob' => $lead->dob,
                    'location' => $lead->location,
                    'company_name' => $lead->company_name,
                    'lead_amount' => number_format($lead->lead_amount, 2, '.', ''),
                    'salary' => $lead->salary ? number_format($lead->salary, 2, '.', '') : null,
                    'success_percentage' => $lead->success_percentage,
                    'expected_month' => $lead->expected_month,
                    'remarks' => $lead->remarks,
                    'status' => $lead->status,
                    'lead_type' => $lead->lead_type,
                    'voice_recording' => $lead->voice_recording,
                    'team_lead_id' => $lead->team_lead_id,
                    'is_personal_lead' => $lead->is_personal_lead,
                    'created_at' => $lead->created_at->toISOString(),
                    'employee' => [
                        'name' => $lead->employee ? $lead->employee->email : null,
                        'profile_photo_url' => null,
                        'pan_card_url' => null,
                        'aadhar_card_url' => null,
                        'signature_url' => null
                    ]
                ]
            ]
        ], 200);
    }

    /**
     * Update an existing lead.
     *
     * @param Request $request
     * @param Lead $lead
     * @return JsonResponse
     */
    public function update(Request $request, Lead $lead): JsonResponse
    {
        // Restrict updates to the lead's employee or team lead
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this lead',
            ], 403);
        }

        // Check if lead is editable (must be personal lead)
        if (!$lead->is_personal_lead) {
            return response()->json([
                'status' => 'error',
                'message' => 'This lead is not editable',
            ], 403);
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
            'lead_type' => 'nullable|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
            'is_personal_lead' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Initialize data with validated request fields
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
            'is_personal_lead',
        ]);

        // Handle voice recording if provided
        if ($request->hasFile('voice_recording')) {
            // Delete old recording if exists
            if ($lead->voice_recording) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $lead->voice_recording));
            }
            $data['voice_recording'] = '/storage/' . $request->file('voice_recording')->store('voice_recordings', 'public');
        }

        // Update the lead with the data
        $lead->update($data);

        $lead->load(['employee', 'teamLead']);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead updated successfully',
            'data' => $lead,
        ], 200);
    }

    /**
     * Delete a lead.
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function destroy(Lead $lead): JsonResponse
    {
        // Restrict deletion to the lead's employee or team lead
        if (Auth::id() !== $lead->employee_id && Auth::id() !== $lead->team_lead_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to delete this lead',
            ], 403);
        }

        $lead->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lead deleted successfully',
        ], 200);
    }
}
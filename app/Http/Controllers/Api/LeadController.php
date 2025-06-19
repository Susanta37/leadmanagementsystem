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
        $validStatuses = ['all', 'pending', 'authorized', 'login', 'approved', 'disbursed', 'rejected'];
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
        if ($user->designation !== 'team_lead' && $user->designation !== 'operations') {
            $query->where('employee_id', $user->id);
        } elseif ($user->designation === 'team_lead') {
            $query->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
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
            'count' => $query->clone()->where('status', 'disbursed')->count(),
            'total_amount' => $query->clone()->where('status', 'disbursed')->sum('lead_amount'),
        ];

        $pendingLeads = [
            'count' => $query->clone()->where('status', 'pending')->count(),
            'total_amount' => $query->clone()->where('status', 'pending')->sum('lead_amount'),
        ];

        $rejectedLeads = [
            'count' => $query->clone()->where('status', 'rejected')->count(),
            'total_amount' => $query->clone()->where('status', 'rejected')->sum('lead_amount'),
        ];

        $authorizedLeads = [
            'count' => $query->clone()->where('status', 'authorized')->count(),
            'total_amount' => $query->clone()->where('status', 'authorized')->sum('lead_amount'),
        ];

        $loginLeads = [
            'count' => $query->clone()->where('status', 'login')->count(),
            'total_amount' => $query->clone()->where('status', 'login')->sum('lead_amount'),
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
                    'authorized_leads' => $authorizedLeads,
                    'login_leads' => $loginLeads,
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
        $user = Auth::user();
        if ($user->designation === 'operations') {
            return response()->json([
                'status' => 'error',
                'message' => 'Operations team cannot create leads',
            ], 403);
        }

        $leadType = $request->input('lead_type');
        
        // Common validation rules
        $commonRules = [
            'lead_type' => 'required|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'team_lead_id' => 'nullable|exists:users,id',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ];

        // Specific validation rules based on lead_type
        $specificRules = [];
        if ($leadType === 'personal_loan' || $leadType === 'home_loan') {
            $specificRules = [
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
            ];
        } elseif ($leadType === 'business_loan') {
            $specificRules = [
                'business_name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'nullable|string|email|max:255',
                'location' => 'required|string|max:255',
                'lead_amount' => 'required|numeric|min:0',
                'turnover_amount' => 'required|numeric|min:5000000',
                'vintage_year' => 'required|integer|min:2',
                'success_percentage' => 'nullable|integer|min:0|max:100',
                'remarks' => 'nullable|string',
            ];
        } elseif ($leadType === 'creditcard_loan') {
            $specificRules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'required|string|email|max:255',
                'bank_name' => 'required|string|max:255',
            ];
        }

        $validator = Validator::make($request->all(), array_merge($commonRules, $specificRules));

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

        // Prepare data based on lead_type
        $data = [
            'employee_id' => Auth::id(),
            'team_lead_id' => $request->team_lead_id,
            'status' => 'personal_lead',
            'lead_type' => $request->lead_type,
            'voice_recording' => $final_voice_recording_path,
            'is_personal_lead' => true,
        ];

        if ($leadType === 'personal_loan' || $leadType === 'home_loan') {
            $data = array_merge($data, [
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
            ]);
        } elseif ($leadType === 'business_loan') {
            $data = array_merge($data, [
                'name' => $request->business_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'location' => $request->location,
                'lead_amount' => $request->lead_amount,
                'turnover_amount' => $request->turnover_amount,
                'vintage_year' => $request->vintage_year,
                'success_percentage' => $request->success_percentage,
                'remarks' => $request->remarks,
            ]);
        } elseif ($leadType === 'creditcard_loan') {
            $data = array_merge($data, [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'bank_name' => $request->bank_name,
            ]);
        }

        $lead = Lead::create($data);
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
        $user = Auth::user();
        // Restrict access based on user role and lead status
        if ($user->designation !== 'team_lead' && $user->designation !== 'operations' && Auth::id() !== $lead->employee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to edit this lead',
            ], 403);
        }

        // Load relationships
        $lead->load(['employee', 'teamLead']);

        $leadData = [
            'id' => $lead->id,
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
        ];

        if ($lead->lead_type === 'personal_loan' || $lead->lead_type === 'home_loan') {
            $leadData = array_merge($leadData, [
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
            ]);
        } elseif ($lead->lead_type === 'business_loan') {
            $leadData = array_merge($leadData, [
                'business_name' => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'location' => $lead->location,
                'lead_amount' => number_format($lead->lead_amount, 2, '.', ''),
                'turnover_amount' => $lead->turnover_amount,
                'vintage_year' => $lead->vintage_year,
                'success_percentage' => $lead->success_percentage,
                'remarks' => $lead->remarks,
            ]);
        } elseif ($lead->lead_type === 'creditcard_loan') {
            $leadData = array_merge($leadData, [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'bank_name' => $lead->bank_name,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lead data retrieved for editing',
            'data' => [
                'lead' => $leadData
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
        $user = Auth::user();

        // Role-based access control
        if ($user->designation !== 'team_lead' && $user->designation !== 'operations' && Auth::id() !== $lead->employee_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this lead',
            ], 403);
        }

        // Validate status transitions based on user role
        $validStatuses = [];
        if ($user->designation === 'team_lead') {
            if ($lead->status === 'pending') {
                $validStatuses = ['authorized', 'rejected'];
            } elseif ($lead->status === 'authorized') {
                $validStatuses = ['login', 'rejected'];
            }
        } elseif ($user->designation === 'operations') {
            if ($lead->status === 'login') {
                $validStatuses = ['approved', 'rejected'];
            } elseif ($lead->status === 'approved') {
                $validStatuses = ['disbursed', 'rejected'];
            }
        } elseif ($lead->is_personal_lead && Auth::id() === $lead->employee_id) {
            $validStatuses = ['pending'];
        }

        // Common validation rules
        $commonRules = [
            'status' => 'sometimes|string|in:' . implode(',', $validStatuses),
            'team_lead_id' => 'sometimes|exists:users,id',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ];

        // Specific validation rules based on lead_type
        $specificRules = [];
        if ($lead->lead_type === 'personal_loan' || $lead->lead_type === 'home_loan') {
            $specificRules = [
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
            ];
        } elseif ($lead->lead_type === 'business_loan') {
            $specificRules = [
                'business_name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:15',
                'email' => 'sometimes|string|email|max:255',
                'location' => 'sometimes|string|max:255',
                'lead_amount' => 'sometimes|numeric|min:0',
                'turnover_amount' => 'sometimes|numeric|min:5000000',
                'vintage_year' => 'sometimes|integer|min:2',
                'success_percentage' => 'sometimes|integer|min:0|max:100',
                'remarks' => 'nullable|string',
            ];
        } elseif ($lead->lead_type === 'creditcard_loan') {
            $specificRules = [
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:15',
                'email' => 'sometimes|string|email|max:255',
                'bank_name' => 'sometimes|string|max:255',
            ];
        }

        $validator = Validator::make($request->all(), array_merge($commonRules, $specificRules));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Initialize data with validated request fields
        $data = $request->only([
            'status',
            'team_lead_id',
        ]);

        // Update is_personal_lead when team lead authorizes
        if ($user->designation === 'team_lead' && $request->status === 'authorized') {
            $data['is_personal_lead'] = false;
        }

        if ($lead->lead_type === 'personal_loan' || $lead->lead_type === 'home_loan') {
            $data = array_merge($data, $request->only([
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
            ]));
        } elseif ($lead->lead_type === 'business_loan') {
            $data = array_merge($data, $request->only([
                'phone',
                'email',
                'location',
                'lead_amount',
                'turnover_amount',
                'vintage_year',
                'success_percentage',
                'remarks',
            ]));
            if ($request->has('business_name')) {
                $data['name'] = $request->business_name;
            }
        } elseif ($lead->lead_type === 'creditcard_loan') {
            $data = array_merge($data, $request->only([
                'name',
                'phone',
                'email',
                'bank_name',
            ]));
        }

        // Handle voice recording if provided
        if ($request->hasFile('voice_recording')) {
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
        $user = Auth::user();
        if ($user->designation !== 'team_lead' && Auth::id() !== $lead->employee_id) {
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
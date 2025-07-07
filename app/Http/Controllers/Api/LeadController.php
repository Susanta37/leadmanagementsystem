<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * Get a list of all leads with enhanced filtering
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
        $includeDeleted = filter_var($request->query('include_deleted', false), FILTER_VALIDATE_BOOLEAN);
        $search = $request->query('search');
        
        // Validate filter parameters
        $validLeadTypes = ['all', 'personal_loan', 'home_loan', 'business_loan', 'creditcard_loan'];
        $validStatuses = ['all', 'personal_lead', 'pending', 'authorized', 'login', 'approved', 'disbursed', 'rejected', 'future_lead'];
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
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid date format',
                ], 400);
            }
        }

        // Base query based on user role
        $query = Lead::query();
        if ($includeDeleted && $user->designation === 'admin') {
            $query->withTrashed();
        } elseif ($includeDeleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can view deleted leads',
            ], 403);
        }
        
        if ($user->designation !== 'team_lead' && $user->designation !== 'operations' && $user->designation !== 'admin') {
            $query->where('employee_id', $user->id);
        } elseif ($user->designation === 'team_lead') {
            $query->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
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
        if ($leadType === 'all') {
            $query->whereNotIn('lead_type', ['creditcard_loan']);
        } else {
            $query->where('lead_type', $leadType);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get all leads with relationships
        $leads = $query->with(['employee', 'teamLead', 'histories' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->orderBy('created_at', 'desc')->get();

        // Transform leads to include combined location
        $leads = $leads->map(function ($lead) {
            $lead->location = implode(', ', array_filter([
                $lead->city,
                $lead->district,
                $lead->state,
            ], function ($value) {
                return !is_null($value) && $value !== '';
            }));
            return $lead;
        });

        // Aggregate data
        $aggregates = [
            'total_leads' => [
                'count' => $query->count(),
                'total_amount' => $query->sum('lead_amount'),
            ],
            'status_breakdown' => []
        ];

        foreach ($validStatuses as $validStatus) {
            if ($validStatus !== 'all') {
                $aggregates['status_breakdown'][$validStatus] = [
                    'count' => $query->clone()->where('status', $validStatus)->count(),
                    'total_amount' => $query->clone()->where('status', $validStatus)->sum('lead_amount'),
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Leads retrieved successfully',
            'data' => [
                'leads' => $leads,
                'aggregates' => $aggregates,
                'filters_applied' => [
                    'lead_type' => $leadType,
                    'status' => $status,
                    'date_filter' => $dateFilter,
                    'start_date' => $startDate ?? null,
                    'end_date' => $endDate ?? null,
                    'include_deleted' => $includeDeleted,
                    'search' => $search,
                ],
            ],
        ], 200);
    }

    /**
     * Create a new lead with history tracking
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user->designation === 'operations' && $user->designation !== 'admin') {
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
            'voice_recording' => 'nullable|file|mimes:mp3,wav,aac,m4a,ogg,flac|max:10240',
            'forward_to' => 'nullable|exists:users,id',
        ];

        // Specific validation rules based on lead_type
        $specificRules = [];
        if ($leadType === 'personal_loan' || $leadType === 'home_loan') {
            $specificRules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
                'state' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'lead_amount' => 'required|numeric|min:0',
                'expected_month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
                'email' => 'nullable|string|email|max:255',
                'dob' => 'nullable|date|before:today',
                'company_name' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric|min:0',
                'success_percentage' => 'nullable|integer|min:0|max:100',
                'remarks' => 'nullable|string|max:1000',
            ];
        } elseif ($leadType === 'business_loan') {
            $specificRules = [
                'business_name' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
                'email' => 'nullable|string|email|max:255',
                'state' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'lead_amount' => 'required|numeric|min:0',
                'turnover_amount' => 'required|numeric|min:5000000',
                'vintage_year' => 'required|integer|min:2',
                'it_return' => 'required|numeric|min:0',
                'success_percentage' => 'nullable|integer|min:0|max:100',
                'remarks' => 'nullable|string|max:1000',
            ];
        } elseif ($leadType === 'creditcard_loan') {
            $specificRules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
                'email' => 'required|string|email|max:255',
                'bank_names' => 'required|array|min:1|max:2',
                'bank_names.*' => 'required|string|max:255|distinct',
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

        // Validate forward_to user designation
        if ($request->has('forward_to')) {
            $forwardToUser = User::find($request->forward_to);
            if (!$forwardToUser || !in_array($forwardToUser->designation, ['admin', 'team_lead', 'operations'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forwarded user must have designation admin, team_lead, or operations',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $voice_recording_path = null;
            if ($request->hasFile('voice_recording')) {
                $voice_recording_path = $request->file('voice_recording')->store('voice_recordings', 'public');
            }
            $final_voice_recording_path = $voice_recording_path ? '/storage/' . $voice_recording_path : null;

            // Prepare base data
            $baseData = [
                'employee_id' => Auth::id(),
                'team_lead_id' => $request->team_lead_id,
                'status' => 'personal_lead',
                'lead_type' => $leadType,
                'voice_recording' => $final_voice_recording_path,
                'is_personal_lead' => true,
            ];

            $leads = [];
            if ($leadType === 'creditcard_loan') {
                // Create a lead for each bank_name
                foreach ($request->bank_names as $bank) {
                    $data = array_merge($baseData, [
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'bank_name' => $bank,
                    ]);

                    $lead = Lead::create($data);
                    LeadHistory::create([
                        'lead_id' => $lead->id,
                        'user_id' => Auth::id(),
                        'action' => 'created',
                        'status' => 'personal_lead',
                        'forwarded_to' => $request->forward_to,
                        'comments' => "Created credit card lead for bank: {$bank}" . 
                                      ($request->forward_to ? " and forwarded to user ID {$request->forward_to}" : ''),
                    ]);

                    if ($request->forward_to) {
                        $lead->update(['team_lead_id' => $request->forward_to]);
                        LeadHistory::create([
                            'lead_id' => $lead->id,
                            'user_id' => Auth::id(),
                            'action' => 'forwarded',
                            'status' => 'personal_lead',
                            'forwarded_to' => $request->forward_to,
                            'comments' => "Forwarded to user ID {$request->forward_to}",
                        ]);
                    }

                    $lead->load(['employee', 'teamLead', 'histories']);
                    $leads[] = $lead;
                }
            } else {
                // Prepare data based on lead_type
                $data = $baseData;
                if ($leadType === 'personal_loan' || $leadType === 'home_loan') {
                    $data = array_merge($data, [
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'dob' => $request->dob,
                        'state' => $request->state,
                        'district' => $request->district,
                        'city' => $request->city,
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
                        'state' => $request->state,
                        'district' => $request->district,
                        'city' => $request->city,
                        'lead_amount' => $request->lead_amount,
                        'turnover_amount' => $request->turnover_amount,
                        'vintage_year' => $request->vintage_year,
                        'it_return' => $request->it_return,
                        'success_percentage' => $request->success_percentage,
                        'remarks' => $request->remarks,
                    ]);
                }

                $lead = Lead::create($data);
                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'action' => 'created',
                    'status' => 'personal_lead',
                    'forwarded_to' => $request->forward_to,
                    'comments' => 'Lead created' . 
                                  ($request->forward_to ? " and forwarded to user ID {$request->forward_to}" : ''),
                ]);

                if ($request->forward_to) {
                    $lead->update(['team_lead_id' => $request->forward_to]);
                    LeadHistory::create([
                        'lead_id' => $lead->id,
                        'user_id' => Auth::id(),
                        'action' => 'forwarded',
                        'status' => 'personal_lead',
                        'forwarded_to' => $request->forward_to,
                        'comments' => "Forwarded to user ID {$request->forward_to}",
                    ]);
                }

                $lead->load(['employee', 'teamLead', 'histories']);
                $leads[] = $lead;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => count($leads) > 1 ? 'Leads created successfully' : 'Lead created successfully',
                'data' => $leads,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            if ($voice_recording_path) {
                Storage::disk('public')->delete($voice_recording_path);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create lead: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get a specific lead by ID with history
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function show(Lead $lead): JsonResponse
    {
        $user = Auth::user();
        if ($lead->trashed() && $user->designation !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead not found',
            ], 404);
        }

        $lead->load([
            'employee',
            'teamLead',
            'histories' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('user');
            }
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead retrieved successfully',
            'data' => $lead,
        ], 200);
    }

    /**
     * Get lead data for editing
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function edit(Lead $lead): JsonResponse
    {
        $user = Auth::user();
        if ($lead->trashed() && $user->designation !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead not found',
            ], 404);
        }

        // Check if lead is editable (personal_lead or future_lead) unless user is admin
        if ($user->designation !== 'admin' && !in_array($lead->status, ['personal_lead', 'future_lead'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead cannot be edited as it is not in personal_lead or future_lead status',
            ], 403);
        }

        if ($user->designation !== 'team_lead' && $user->designation !== 'operations' && 
            $user->designation !== 'admin' && !($lead->is_personal_lead && Auth::id() === $lead->employee_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to edit this lead',
            ], 403);
        }

        $lead->load([
            'employee',
            'teamLead',
            'histories' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('user');
            }
        ]);

        $leadData = [
            'id' => $lead->id,
            'status' => $lead->status,
            'lead_type' => $lead->lead_type,
            'voice_recording' => $lead->voice_recording,
            'team_lead_id' => $lead->team_lead_id,
            'is_personal_lead' => $lead->is_personal_lead,
            'created_at' => $lead->created_at->toISOString(),
            'deleted_at' => $lead->deleted_at ? $lead->deleted_at->toISOString() : null,
            'employee' => [
                'name' => $lead->employee ? $lead->employee->email : null,
                'profile_photo_url' => null,
                'pan_card_url' => null,
                'aadhar_card_url' => null,
                'signature_url' => null,
            ],
            'histories' => $lead->forwardedHistories,
        ];

        if ($lead->lead_type === 'personal_loan' || $lead->lead_type === 'home_loan') {
            $leadData = array_merge($leadData, [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'dob' => $lead->dob,
                'state' => $lead->state,
                'district' => $lead->district,
                'city' => $lead->city,
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
                'state' => $lead->state,
                'district' => $lead->district,
                'city' => $lead->city,
                'lead_amount' => number_format($lead->lead_amount, 2, '.', ''),
                'turnover_amount' => number_format($lead->turnover_amount, 2, '.', ''),
                'vintage_year' => $lead->vintage_year,
                'it_return' => number_format($lead->it_return, 2, '.', ''),
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
                'lead' => $leadData,
            ],
        ], 200);
    }

    /**
     * Update an existing lead with history tracking
     *
     * @param Request $request
     * @param Lead $lead
     * @return JsonResponse
     */
    public function update(Request $request, Lead $lead): JsonResponse
    {
        $user = Auth::user();

        if ($lead->trashed() && $user->designation !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead not found',
            ], 404);
        }

        // Check if lead is editable (personal_lead or future_lead) unless user is admin
        if ($user->designation !== 'admin' && !in_array($lead->status, ['personal_lead', 'future_lead'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead cannot be updated as it is not in personal_lead or future_lead status',
            ], 403);
        }

        // Authorization check
        if ($user->designation !== 'team_lead' && $user->designation !== 'operations' && 
            $user->designation !== 'admin' && !($lead->is_personal_lead && Auth::id() === $lead->employee_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this lead',
            ], 403);
        }

        // Define status transitions
        $statusTransitions = [
            'team_lead' => [
                'personal_lead' => ['pending', 'rejected', 'future_lead'],
                'future_lead' => ['pending', 'rejected'],
                'pending' => ['authorized', 'rejected'],
                'authorized' => ['login', 'rejected'],
            ],
            'operations' => [
                'login' => ['approved', 'rejected'],
                'approved' => ['disbursed', 'rejected'],
            ],
            'admin' => ['personal_lead', 'pending', 'authorized', 'login', 'approved', 'disbursed', 'rejected', 'future_lead'],
            'employee' => ['personal_lead', 'future_lead'],
        ];

        $validStatuses = [];
        if ($user->designation === 'admin') {
            $validStatuses = $statusTransitions['admin'];
        } elseif ($user->designation === 'team_lead') {
            $validStatuses = $statusTransitions['team_lead'][$lead->status] ?? [];
        } elseif ($user->designation === 'operations') {
            $validStatuses = $statusTransitions['operations'][$lead->status] ?? [];
        } elseif ($lead->is_personal_lead && Auth::id() === $lead->employee_id) {
            $validStatuses = $statusTransitions['employee'];
        }

        // Common validation rules
        $commonRules = [
            'status' => 'sometimes|string|in:' . implode(',', $validStatuses),
            'team_lead_id' => 'sometimes|nullable|exists:users,id',
            'voice_recording' => 'nullable|file|mimes:mp3,wav,aac,m4a,ogg,flac|max:20480',
            'forward_to' => 'nullable|exists:users,id',
            'forward_notes' => 'nullable|string|max:5000',
        ];

        // Specific validation rules based on lead_type
        $specificRules = [];
        if ($lead->lead_type === 'personal_loan' || $lead->lead_type === 'home_loan') {
            $specificRules = [
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|regex:/^\+?[1-9]\d{1,14}$/',
                'email' => 'sometimes|nullable|string|email|max:255',
                'dob' => 'nullable|date|before:today',
                'state' => 'sometimes|string|max:255',
                'district' => 'sometimes|string|max:255',
                'city' => 'sometimes|string|max:255',
                'company_name' => 'sometimes|nullable|string|max:255',
                'lead_amount' => 'sometimes|amount',
                'salary' => 'sometimes|nullable|numeric|min:0',
                'success_percentage' => 'sometimes|nullable|integer|min:0|max:100',
                'expected_month' => 'sometimes|nullable|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
                'remarks' => 'sometimes|nullable|string|max:1000',
            ];
        } elseif ($lead->lead_type === 'business_loan') {
            $specificRules = [
                'business_name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|regex:/^\+?[1-9]\d{1,14}$/',
                'email' => 'sometimes|nullable|string|email|max:255',
                'state' => 'sometimes|string|max:255',
                'district' => 'sometimes|string|max:255',
                'city' => 'sometimes|string|max:255',
                'lead_amount' => 'sometimes|amount',
                'turnover_amount' => 'sometimes|nullable|numeric|min:5000000',
                'vintage_year' => 'sometimes|nullable|integer|min:2',
                'it_return' => 'sometimes|nullable|numeric|min:0',
                'success_percentage' => 'sometimes|nullable|integer|min:0|max:100',
                'remarks' => 'sometimes|nullable|string|max:1000',
            ];
        } elseif ($lead->lead_type === 'creditcard_loan') {
            $specificRules = [
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|regex:/^\+?[1-9]\d{1,14}$/',
                'email' => 'sometimes|string|email|max:255',
                'bank_name' => 'sometimes|string|max:255',
            ];
        }

        // Register custom amount validator
        Validator::extend('amount', function ($attribute, $value) {
            return is_numeric($value) && $value >= 0 && preg_match('/^\d+(\.\d{1,2})?$/', $value);
        }, 'The :attribute must be a valid amount with up to 2 decimal places.');

        $validator = Validator::make($request->all(), array_merge($commonRules, $specificRules));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate forward_to user designation
        if ($request->has('forward_to')) {
            $forwardToUser = User::find($request->forward_to);
            if (!$forwardToUser || !in_array($forwardToUser->designation, ['admin', 'team_lead', 'operations'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forwarded user must have designation admin, team_lead, or operations',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            // Initialize data with validated fields
            $data = $request->only(array_keys(array_merge($commonRules, $specificRules)));

            // Update is_personal_lead when team lead authorizes
            if ($user->designation === 'team_lead' && $request->status === 'authorized') {
                $data['is_personal_lead'] = false;
            }

            if ($lead->lead_type === 'business_loan' && $request->has('business_name')) {
                $data['name'] = $request->business_name;
            }

            // Handle voice recording
            if ($request->hasFile('voice_recording')) {
                if ($lead->voice_recording) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $lead->voice_recording));
                }
                $data['voice_recording'] = '/storage/' . $request->file('voice_recording')->store('voice_recordings', 'public');
                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'action' => 'voice_recording_updated',
                    'status' => $lead->status,
                    'comments' => 'Voice recording updated',
                ]);
            }

            // Log changes
            $changes = [];
            foreach ($data as $key => $value) {
                if ($key !== 'forward_to' && $key !== 'forward_notes' && 
                    $lead->$key != $value && !(is_null($lead->$key) && is_null($value))) {
                    $changes[$key] = [
                        'old' => $lead->$key,
                        'new' => $value,
                    ];
                }
            }

            // Log status change
            if ($request->has('status') && $request->status !== $lead->status) {
                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'action' => 'status_change',
                    'status' => $request->status,
                    'comments' => "Status changed from {$lead->status} to {$request->status}",
                ]);
            }

            // Log forwarding
            if ($request->has('forward_to') && $request->forward_to != $lead->team_lead_id) {
                $data['team_lead_id'] = $request->forward_to;
                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'action' => 'forwarded',
                    'status' => $lead->status,
                    'forwarded_to_user_id' => $request->forward_to,
                    'comments' => $request->forward_notes
                        ? "Forwarded to user ID {$request->forward_to}: {$request->forward_notes}"
                        : "Forwarded to user ID {$request->forward_to}",
                ]);
            }

            // Log other changes
            if ($changes) {
                LeadHistory::create([
                    'lead_id' => $lead->id,
                    'user_id' => Auth::id(),
                    'action' => 'updated',
                    'status' => $lead->status,
                    'comments' => 'Updated fields: ' . json_encode($changes),
                ]);
            }

            // Update lead
            $lead->update($data);

            $lead->load([
                'employee',
                'teamLead',
                'histories' => function ($query) {
                    $query->orderBy('created_at', 'desc')->with('user');
                }
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Lead updated successfully',
                'data' => $lead,
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update lead: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete a lead with history
     *
     * @param Lead $lead
     * @return JsonResponse
     */
    public function destroy(Lead $lead): JsonResponse
    {
        $user = Auth::user();
        
        // Check if lead is deletable (personal_lead or future_lead) unless user is admin
        if ($user->designation !== 'admin' && !in_array($lead->status, ['personal_lead', 'future_lead'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead cannot be deleted as it is not in personal_lead or future_lead status',
            ], 403);
        }

        // Authorization check
        if ($user->designation !== 'team_lead' && $user->designation !== 'admin' && 
            !($lead->is_personal_lead && Auth::id() === $lead->employee_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to delete this lead',
            ], 403);
        }

        if ($lead->trashed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead is already deleted',
            ], 422);
        }

        try {
            DB::beginTransaction();

            LeadHistory::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'soft_deleted',
                'status' => $lead->status,
                'comments' => 'Lead soft deleted',
            ]);

            $lead->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Lead deleted successfully',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete lead: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted lead with history
     *
     * @param int $leadId
     * @return JsonResponse
     */
    public function restore(int $leadId): JsonResponse
    {
        $user = Auth::user();
        if ($user->designation !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to restore leads',
            ], 403);
        }

        $lead = Lead::withTrashed()->where('id', $leadId)->firstOrFail();
        
        if (!$lead->trashed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead is not deleted',
            ], 422);
        }

        try {
            DB::beginTransaction();

            LeadHistory::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'restored',
                'status' => $lead->status,
                'comments' => 'Lead restored from soft deletion',
            ]);

            $lead->restore();

            $lead->load([
                'employee',
                'teamLead',
                'histories' => function ($query) {
                    $query->orderBy('created_at', 'desc')->with('user');
                }
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Lead restored successfully',
                'data' => $lead,
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to restore lead: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Permanently delete a lead with history
     *
     * @param int $leadId
     * @return JsonResponse
     */
    public function forceDelete(int $leadId): JsonResponse
    {
        $user = Auth::user();
        if ($user->designation !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to permanently delete leads',
            ], 403);
        }

        $lead = Lead::withTrashed()->where('id', $leadId)->firstOrFail();
        
        try {
            DB::beginTransaction();

            LeadHistory::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'force_deleted',
                'status' => $lead->status,
                'comments' => 'Lead permanently deleted',
            ]);

            if ($lead->voice_recording) {
                Storage::disk('public')->delete(str_replace('/storage/', '', trim($lead->voice_recording)));
            }

            $lead->forceDelete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Lead permanently deleted successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to permanently delete lead: ' . $e->getMessage(),
            ], 500);
        }
    }
}
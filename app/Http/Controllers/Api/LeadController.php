<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
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

        // Base query based on user role
        $query = Lead::query();
        if ($user->designation !== 'team_lead') {
            $query->where('employee_id', $user->id);
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
            'email' => 'required|string|email|max:255',
            'dob' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'lead_amount' => 'required|numeric|min:0',
            'salary' => 'nullable|numeric|min:0',
            'success_percentage' => 'required|integer|min:0|max:100',
            'expected_month' => 'nullable|date',
            'remarks' => 'nullable|string',
            'status' => 'required|string|in:pending,approved,rejected,completed',
            'team_lead_id' => 'required|exists:users,id',
            'lead_type' => 'required|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check for existing lead with same email or phone
        $exists = Lead::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'A lead with this email or phone number already exists.',
                'errors' => [
                    'email' => ['Email or phone number already used for another lead.'],
                    'phone' => ['Email or phone number already used for another lead.'],
                ],
            ], 409); // 409 Conflict
        }

          $voiceRecordingPath = null;
        if ($request->hasFile('voice_recording')) {
            $voiceRecordingPath = $request->file('voice_recording')->store('voice_recordings', 'public');
        }
        $finalvoiceRecordingPath= '/storage/' . $voiceRecordingPath; 
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
            'status' => $request->status,
            'lead_type' => $request->lead_type,
            'voice_recording' => $finalvoiceRecordingPath,
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
            'expected_month' => 'nullable|date',
            'remarks' => 'nullable|string',
            'status' => 'sometimes|string|in:pending,approved,rejected,completed',
            'team_lead_id' => 'sometimes|exists:users,id',
            'lead_type' => 'required|string|in:personal_loan,home_loan,business_loan,creditcard_loan',
            'voice_recording' => 'nullable|file|mimes:mp3,wav|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lead->update($request->only([
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
        ]));
         if ($request->hasFile('voice_recording')) {
            // Delete old recording if exists
            if ($lead->voice_recording) {
                Storage::disk('public')->delete($lead->voice_recording);
            }
            $data['voice_recording'] = $request->file('voice_recording')->store('voice_recordings', 'public');
        }

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

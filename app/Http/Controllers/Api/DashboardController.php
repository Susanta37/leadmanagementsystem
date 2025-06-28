<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Get filter parameters
        $leadType = $request->query('lead_type', 'all');
        $status = $request->query('status', 'all');
        $expectedMonth = $request->query('expected_month', Carbon::now()->format('F')); // e.g., 'June'
        $currentYear = Carbon::now()->year; // e.g., 2025

        // Validate filter parameters
        $validLeadTypes = ['all', 'personal_loan', 'business_loan', 'home_loan', 'creditcard_loan'];
        $validStatuses = ['all', 'personal_lead', 'authorized', 'login', 'approved', 'disbursed', 'rejected', 'future_lead'];
        $validExpectedMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

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

        if (!in_array($expectedMonth, $validExpectedMonths)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid expected month',
            ], 400);
        }

        // Base query based on user role
        $query = Lead::query();
        if ($user->designation !== 'team_lead') {
            $query->where('employee_id', $user->id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
        }

        // Total leads (all time, excluding creditcard_loan)
        $totalLeadsQuery = Lead::query();
        if ($user->designation !== 'team_lead') {
            $totalLeadsQuery->where('employee_id', $user->id);
        } else {
            $totalLeadsQuery->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
        }
        $totalLeadsQuery->whereNotIn('lead_type', ['creditcard_loan']);

        $totalLeads = [
            'count' => $totalLeadsQuery->count(),
            'total_amount' => $totalLeadsQuery->sum('lead_amount') ?? 0,
        ];

        // Apply current year filter to main query
        $query->whereYear('created_at', $currentYear);

        // Apply expected month filter
        $query->where('expected_month', $expectedMonth);

        // Apply lead type filter
        if ($leadType !== 'all') {
            $query->where('lead_type', $leadType);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get status aggregates
        $personalLeads = [
            'count' => $query->clone()->where('status', 'personal_lead')->whereNotIn('lead_type', ['creditcard_loan'])->count(),
            'total_amount' => $query->clone()->where('status', 'personal_lead')->whereNotIn('lead_type', ['creditcard_loan'])->sum('lead_amount') ?? 0,
        ];

        $authorizedLeads = [
            'count' => $query->clone()->where('status', 'authorized')->count(),
            'total_amount' => $query->clone()->where('status', 'authorized')->sum('lead_amount') ?? 0,
        ];

        $loginLeads = [
            'count' => $query->clone()->where('status', 'login')->count(),
            'total_amount' => $query->clone()->where('status', 'login')->sum('lead_amount') ?? 0,
        ];

        $approvedLeads = [
            'count' => $query->clone()->where('status', 'approved')->count(),
            'total_amount' => $query->clone()->where('status', 'approved')->sum('lead_amount') ?? 0,
        ];

        $disbursedLeads = [
            'count' => $query->clone()->where('status', 'disbursed')->count(),
            'total_amount' => $query->clone()->where('status', 'disbursed')->sum('lead_amount') ?? 0,
        ];

        $rejectedLeads = [
            'count' => $query->clone()->where('status', 'rejected')->count(),
            'total_amount' => $query->clone()->where('status', 'rejected')->sum('lead_amount') ?? 0,
        ];

        // Future leads with expected month filter
        $futureLeadsQuery = Lead::query();
        if ($user->designation !== 'team_lead') {
            $futureLeadsQuery->where('employee_id', $user->id);
        } else {
            $futureLeadsQuery->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
        }
        $futureLeadsQuery->where('status', 'future_lead')
                         ->whereYear('created_at', $currentYear)
                         ->where('expected_month', $expectedMonth);

        $futureLeads = [
            'count' => $futureLeadsQuery->count(),
            'total_amount' => $futureLeadsQuery->sum('lead_amount') ?? 0,
        ];

        // Lead type breakdowns
        $leadTypes = ['personal_loan', 'business_loan', 'home_loan', 'creditcard_loan'];
        $leadTypeBreakdown = [];

        $baseQuery = Lead::query();
        if ($user->designation !== 'team_lead') {
            $baseQuery->where('employee_id', $user->id);
        } else {
            $baseQuery->where(function ($q) use ($user) {
                $q->where('employee_id', $user->id)
                  ->orWhere('team_lead_id', $user->id);
            });
        }

        // Apply current year and expected month filters to lead type breakdown
        $baseQuery->whereYear('created_at', $currentYear)
                  ->where('expected_month', $expectedMonth);

        foreach ($leadTypes as $type) {
            $typeQuery = $baseQuery->clone()->where('lead_type', $type);
            if ($status !== 'all') {
                $typeQuery->where('status', $status);
            }

            if ($type === 'creditcard_loan') {
                // Credit card loan breakdown: applied (personal_lead), approved, rejected
                $leadTypeBreakdown[$type] = [
                    'applied' => [
                        'count' => $typeQuery->clone()->where('status', 'personal_lead')->count(),
                        'total_amount' => 0, // No lead_amount for creditcard_loan
                    ],
                    'approved' => [
                        'count' => $typeQuery->clone()->where('status', 'approved')->count(),
                        'total_amount' => 0, // No lead_amount for creditcard_loan
                    ],
                    'rejected' => [
                        'count' => $typeQuery->clone()->where('status', 'rejected')->count(),
                        'total_amount' => 0, // No lead_amount for creditcard_loan
                    ],
                ];
            } else {
                // Other lead types: include lead details
                $leads = $typeQuery->with(['employee'])->get()->map(function ($lead) {
                    return [
                        'id' => $lead->id,
                        'name' => $lead->name,
                        'lead_amount' => $lead->lead_amount ? number_format($lead->lead_amount, 2, '.', '') : null,
                        'status' => $lead->status,
                        'expected_month' => $lead->expected_month,
                        'created_at' => $lead->created_at->toISOString(),
                        'location' => implode(', ', array_filter([
                            $lead->city,
                            $lead->district,
                            $lead->state,
                        ], function ($value) {
                            return !is_null($value) && $value !== '';
                        })),
                        'employee' => [
                            'name' => $lead->employee ? $lead->employee->name : null,
                            'profile_photo_url' => $lead->employee ? $lead->employee->profile_photo_url : null,
                            'pan_card_url' => $lead->employee ? $lead->employee->pan_card_url : null,
                            'aadhar_card_url' => $lead->employee ? $lead->employee->aadhar_card_url : null,
                            'signature_url' => $lead->employee ? $lead->employee->signature_url : null,
                        ],
                    ];
                });

                $leadTypeBreakdown[$type] = [
                    'count' => $typeQuery->count(),
                    'total_amount' => $typeQuery->sum('lead_amount') ?? 0,
                    'leads' => $leads,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'designation' => $user->designation,
                ],
                'aggregates' => [
                    'total_leads' => $totalLeads,
                    'personal_leads' => $personalLeads,
                    'authorized_leads' => $authorizedLeads,
                    'login_leads' => $loginLeads,
                    'approved_leads' => $approvedLeads,
                    'disbursed_leads' => $disbursedLeads,
                    'rejected_leads' => $rejectedLeads,
                ],
                'lead_type_breakdown' => $leadTypeBreakdown,
                'future_leads' => $futureLeads,
                'filters_applied' => [
                    'lead_type' => $leadType,
                    'status' => $status,
                    'year' => $currentYear,
                    'expected_month' => $expectedMonth,
                ],
            ],
        ], 200);
    }
}
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
        $dateFilter = $request->query('date_filter', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $expectedMonth = $request->query('expected_month', 'all');

        // Validate filter parameters
        $validLeadTypes = ['all', 'personal_loan', 'business_loan', 'home_loan', 'creditcard_loan'];
        $validStatuses = ['all', 'personal_lead', 'authorized', 'approved', 'completed', 'rejected'];
        $validDateFilters = ['this_month', 'this_week', 'this_year', 'date_range'];
        $validExpectedMonths = ['all', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

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

        if (!in_array($expectedMonth, $validExpectedMonths)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid expected month',
            ], 400);
        }

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
        } else {
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

        // Apply expected month filter
        if ($expectedMonth !== 'all') {
            $query->where('expected_month', $expectedMonth);
        }

        // Apply lead type filter
        if ($leadType !== 'all') {
            $query->where('lead_type', $leadType);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get aggregates
        $totalLeads = [
            'count' => $query->count(),
            'total_amount' => $query->sum('lead_amount') ?? 0,
        ];

        $personalLeads = [
            'count' => $query->clone()->where('status', 'personal_lead')->count(),
            'total_amount' => $query->clone()->where('status', 'personal_lead')->sum('lead_amount') ?? 0,
        ];

        $authorizedLeads = [
            'count' => $query->clone()->where('status', 'authorized')->count(),
            'total_amount' => $query->clone()->where('status', 'authorized')->sum('lead_amount') ?? 0,
        ];

        $approvedLeads = [
            'count' => $query->clone()->where('status', 'approved')->count(),
            'total_amount' => $query->clone()->where('status', 'approved')->sum('lead_amount') ?? 0,
        ];

        $completedLeads = [
            'count' => $query->clone()->where('status', 'completed')->count(),
            'total_amount' => $query->clone()->where('status', 'completed')->sum('lead_amount') ?? 0,
        ];

        $rejectedLeads = [
            'count' => $query->clone()->where('status', 'rejected')->count(),
            'total_amount' => $query->clone()->where('status', 'rejected')->sum('lead_amount') ?? 0,
        ];

        // Get lead type breakdowns
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

        // Apply date filter to lead type breakdown
        if ($dateFilter === 'this_month') {
            $baseQuery->whereYear('created_at', $now->year)
                      ->whereMonth('created_at', $now->month);
        } elseif ($dateFilter === 'this_week') {
            $baseQuery->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        } elseif ($dateFilter === 'this_year') {
            $baseQuery->whereYear('created_at', $now->year);
        } elseif ($dateFilter === 'date_range') {
            $baseQuery->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        // Apply expected month filter to lead type breakdown
        if ($expectedMonth !== 'all') {
            $baseQuery->where('expected_month', $expectedMonth);
        }

        foreach ($leadTypes as $type) {
            $typeQuery = $baseQuery->clone()->where('lead_type', $type);
            if ($status !== 'all') {
                $typeQuery->where('status', $status);
            }

            $leads = $typeQuery->with(['employee'])->get()->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'lead_amount' => $lead->lead_amount,
                    'status' => $lead->status,
                    'expected_month' => $lead->expected_month,
                    'created_at' => $lead->created_at,
                    'employee' => [
                        'name' => $lead->employee->name,
                        'profile_photo_url' => $lead->employee->profile_photo_url,
                        'pan_card_url' => $lead->employee->pan_card_url,
                        'aadhar_card_url' => $lead->employee->aadhar_card_url,
                        'signature_url' => $lead->employee->signature_url,
                    ],
                ];
            });

            $leadTypeBreakdown[$type] = [
                'count' => $typeQuery->count(),
                'total_amount' => $typeQuery->sum('lead_amount') ?? 0,
                'leads' => $leads,
            ];
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
                    'approved_leads' => $approvedLeads,
                    'completed_leads' => $completedLeads,
                    'rejected_leads' => $rejectedLeads,
                ],
                'lead_type_breakdown' => $leadTypeBreakdown,
                'filters_applied' => [
                    'lead_type' => $leadType,
                    'status' => $status,
                    'date_filter' => $dateFilter,
                    'start_date' => $startDate ?? null,
                    'end_date' => $endDate ?? null,
                    'expected_month' => $expectedMonth,
                ],
            ],
        ], 200);
    }
}
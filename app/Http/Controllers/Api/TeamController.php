<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of team leads and their team members with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = User::whereHas('teamMembers');

        // Filter by department
        if ($request->has('department')) {
            $query->where('department', $request->input('department'));
        }

        // Filter by specific team lead ID
        if ($request->has('lead_id')) {
            $query->where('id', $request->input('lead_id'));
        }

        $teamLeads = $query->with(['teamMembers' => function ($q) {
            $q->select(
                'id', 'name', 'email', 'phone', 'designation', 'department',
                'profile_photo', 'address', 'pan_card', 'aadhar_card',
                'signature', 'created_by', 'team_lead_id'
            );
        }])->select(
            'id', 'name', 'email', 'phone', 'designation', 'department',
            'profile_photo', 'address', 'pan_card', 'aadhar_card',
            'signature', 'created_by'
        )->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Team data fetched successfully.',
            'data' => $teamLeads
        ]);
    }
}

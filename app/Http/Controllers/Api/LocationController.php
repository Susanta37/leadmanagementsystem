<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Get all active districts for a given state.
     *
     * @param int $stateId
     * @return JsonResponse
     */


      public function getStates(): JsonResponse
    {
        $states = State::where('status', 'Active')
                       ->select('state_id', 'state_title')
                       ->orderBy('state_title', 'asc')
                       ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'States retrieved successfully',
            'data' => $states,
        ], 200);
    }
    public function getDistricts($stateId): JsonResponse
    {
        $state = State::where('state_id', $stateId)
                      ->where('status', 'Active')
                      ->first();

        if (!$state) {
            return response()->json([
                'status' => 'error',
                'message' => 'State not found or inactive',
                'data' => [],
            ], 404);
        }

        $districts = $state->districts()->select('districtid', 'district_title')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Districts retrieved successfully',
            'data' => $districts,
        ], 200);
    }

    /**
     * Get all active cities for a given district.
     *
     * @param int $districtId
     * @return JsonResponse
     */
    public function getCities($districtId): JsonResponse
    {
        $district = District::where('districtid', $districtId)
                           ->where('district_status', 'Active')
                           ->first();

        if (!$district) {
            return response()->json([
                'status' => 'error',
                'message' => 'District not found or inactive',
                'data' => [],
            ], 404);
        }

        $cities = $district->cities()->select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Cities retrieved successfully',
            'data' => $cities,
        ], 200);
    }
}
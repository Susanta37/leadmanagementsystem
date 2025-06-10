<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    // Apply for leave
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $totalDays = now()->parse($request->start_date)->diffInDays($request->end_date) + 1;

        $leave = Leave::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'applied_to' => $user->team_lead_id ?? $user->created_by,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Leave applied successfully.', 'data' => $leave]);
    }

    // List user leaves
    public function index()
    {
        $user = Auth::user();

        $leaves = Leave::where('user_id', $user->id)->orderByDesc('created_at')->get();

        return response()->json(['status' => 'success', 'leaves' => $leaves]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Attendance::with('employee')
            ->where('employee_id', $user->id);

        // Apply filters
        if ($request->has('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->has('status')) {
            if ($request->status === 'present') {
                $query->whereNotNull('check_in');
            } elseif ($request->status === 'absent') {
                $query->whereNull('check_in');
            }
        }

        if ($request->has(['from_date', 'to_date'])) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $records = $query->orderBy('date', 'desc')->get();

        // Summary
        $totalDays = $records->count();
        $present = $records->whereNotNull('check_in')->count();
        $absent = $totalDays - $present;

        $today = $records->where('date', today()->toDateString())->first();
        $checkIn = optional($today)->check_in;
        $checkOut = optional($today)->check_out;

        // Calculate total working hours for today
        $workingHours = null;
        if ($checkIn && $today && $today->sessions) {
            $totalMinutes = 0;
            $sessions = is_string($today->sessions) ? json_decode($today->sessions, true) : $today->sessions;
            $sessions = is_array($sessions) ? $sessions : [];
            foreach ($sessions as $session) {
                if (!empty($session['check_in']) && !empty($session['check_out'])) {
                    $totalMinutes += Carbon::parse($session['check_in'])->diffInMinutes($session['check_out']);
                }
            }
            $workingHours = $totalMinutes / 60;
        }

        return response()->json([
            'status' => 'success',
            'summary' => [
                'total_days' => $totalDays,
                'present' => $present,
                'absent' => $absent,
                'today_check_in' => $checkIn,
                'today_check_out' => $checkOut,
                'working_hours_today' => $workingHours ? round($workingHours, 2) : null
            ],
            'records' => $records->map(function ($record) {
                $sessions = is_string($record->sessions) ? json_decode($record->sessions, true) : $record->sessions;
                $sessions = is_array($sessions) ? $sessions : [];
                $totalWorkedHours = 0;
                foreach ($sessions as $session) {
                    if (!empty($session['check_in']) && !empty($session['check_out'])) {
                        $totalWorkedHours += Carbon::parse($session['check_in'])->diffInMinutes($session['check_out']) / 60;
                    }
                }
                return [
                    'employee_name' => $record->employee->name,
                    'date' => $record->date,
                    'check_in' => $record->check_in,
                    'check_out' => $record->check_out,
                    'check_in_location' => $record->check_in_location,
                    'check_out_location' => $record->check_out_location,
                    'check_in_coordinates' => $record->check_in_coordinates,
                    'check_out_coordinates' => $record->check_out_coordinates,
                    'notes' => $record->notes,
                    'checkin_image' => $record->checkin_image,
                    'checkout_image' => $record->checkout_image,
                    'reason' => $record->reason,
                    'worked_hours' => round($totalWorkedHours, 2),
                    'sessions' => $sessions
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        Log::info('Starting attendance store', [
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);

        // Check if today is Sunday
        if ($today->isSunday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance is not allowed on Sunday.',
                'button_action' => 'none'
            ], 403);
        }

        // Check for approved leave
        $leaveToday = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($leaveToday) {
            Log::info('User is on approved leave', ['leave_id' => $leaveToday->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance not allowed. You are on approved leave today.',
                'button_action' => 'none'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'checkin_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'check_in_location' => 'nullable|string|max:255',
            'check_in_coordinates' => 'required|string|regex:/^[-]?[0-9]{1,3}\.[0-9]{6},[-]?[0-9]{1,3}\.[0-9]{6}$/',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for attendance store', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'button_action' => 'checkin'
            ], 422);
        }

        // Prepare session data
        $sessionData = [
            'check_in' => now()->toDateTimeString(),
            'check_in_location' => $request->check_in_location,
            'check_in_coordinates' => $request->check_in_coordinates,
            'check_out' => null,
            'check_out_location' => null,
            'check_out_coordinates' => null
        ];
        $encodedSessions = json_encode([$sessionData]);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON encoding failed for session data', [
                'error' => json_last_error_msg(),
                'data' => $sessionData
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process check-in due to data encoding error.',
                'button_action' => 'checkin'
            ], 500);
        }

        Log::info('Prepared session data', ['session_data' => $sessionData, 'encoded_sessions' => $encodedSessions]);

        // Find or create today's attendance record
        $attendance = Attendance::where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance && $attendance->check_in && !$attendance->check_out) {
            Log::info('User already checked in', ['attendance_id' => $attendance->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'You are already checked in. Please check out before checking in again.',
                'button_action' => 'checkout',
                'attendance_id' => $attendance->id
            ], 403);
        }

        if (!$attendance) {
            // First check-in of the day
            $attributes = [
                'employee_id' => $user->id,
                'date' => $today,
                'check_in' => now(),
                'check_in_location' => $request->check_in_location,
                'check_in_coordinates' => $request->check_in_coordinates,
                'checkin_image' => $request->hasFile('checkin_image') 
                    ? '/storage/' . $request->file('checkin_image')->store('attendance/checkin', 'public')
                    : null,
                'notes' => $request->notes,
                'reason' => 'Initial check-in',
                'sessions' => $encodedSessions
            ];
            Log::info('Creating new attendance record', $attributes);
            $attendance = Attendance::create($attributes);
        } else {
            // Re-check-in (e.g., after lunch break)
            $sessions = is_string($attendance->sessions) ? json_decode($attendance->sessions, true) : $attendance->sessions;
            if (!is_array($sessions)) {
                Log::warning('Invalid sessions data, resetting to empty array', [
                    'attendance_id' => $attendance->id,
                    'sessions' => $attendance->sessions
                ]);
                $sessions = [];
            }
            $sessions[] = $sessionData;
            $encodedSessions = json_encode($sessions);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON encoding failed for updated sessions', [
                    'error' => json_last_error_msg(),
                    'data' => $sessions
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to process re-check-in due to data encoding error.',
                    'button_action' => 'checkin'
                ], 500);
            }
            $attributes = [
                'check_in' => now(),
                'check_out' => null, // Reset check_out for re-check-in
                'check_in_location' => $request->check_in_location,
                'check_in_coordinates' => $request->check_in_coordinates,
                'checkin_image' => $request->hasFile('checkin_image') 
                    ? '/storage/' . $request->file('checkin_image')->store('attendance/checkin', 'public')
                    : $attendance->checkin_image,
                'notes' => $request->notes ?? $attendance->notes,
                'reason' => 'Re-check-in',
                'sessions' => $encodedSessions
            ];
            Log::info('Updating existing attendance record', $attributes);
            $attendance->update($attributes);
        }

        Log::info('Attendance check-in recorded', [
            'attendance_id' => $attendance->id,
            'sessions' => $attendance->sessions
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in recorded successfully.',
            'button_action' => 'checkout',
            'data' => $attendance,
            'attendance_id' => $attendance->id
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $user = Auth::user();

        // Authorization check
        if ($attendance->employee_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'button_action' => 'none'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'check_out_location' => 'nullable|string|max:255',
            'check_out_coordinates' => 'required|string|regex:/^[-]?[0-9]{1,3}\.[0-9]{6},[-]?[0-9]{1,3}\.[0-9]{6}$/',
            'checkout_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'button_action' => 'checkout'
            ], 422);
        }

        // Update the latest session for check-out
        $sessions = is_string($attendance->sessions) ? json_decode($attendance->sessions, true) : $attendance->sessions;
        $sessions = is_array($sessions) ? $sessions : [];
        if (!empty($sessions)) {
            $latestSession = &$sessions[count($sessions) - 1];
            if (!empty($latestSession['check_out'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Already checked out for the current session.',
                    'button_action' => 'checkin'
                ], 403);
            }
            $latestSession['check_out'] = now()->toDateTimeString();
            $latestSession['check_out_location'] = $request->check_out_location;
            $latestSession['check_out_coordinates'] = $request->check_out_coordinates;
        } else {
            Log::warning('No sessions found for check-out', ['attendance_id' => $attendance->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'No active session found to check out.',
                'button_action' => 'checkin'
            ], 403);
        }

        // Handle image upload
        $checkoutImagePath = $attendance->checkout_image;
        if ($request->hasFile('checkout_image')) {
            if ($attendance->checkout_image) {
                $oldPath = str_replace('/storage/', '', $attendance->checkout_image);
                Storage::disk('public')->delete($oldPath);
            }
            $storedPath = $request->file('checkout_image')->store('attendance/checkout', 'public');
            $checkoutImagePath = '/storage/' . $storedPath;
        }

        // Calculate total worked hours
        $totalWorkedHours = 0;
        foreach ($sessions as $session) {
            if (!empty($session['check_in']) && !empty($session['check_out'])) {
                $totalWorkedHours += Carbon::parse($session['check_in'])->diffInMinutes($session['check_out']) / 60;
            }
        }

        // Update attendance record
        $attributes = [
            'check_out' => now(),
            'check_out_location' => $request->check_out_location,
            'check_out_coordinates' => $request->check_out_coordinates,
            'checkout_image' => $checkoutImagePath,
            'notes' => $request->notes ?? $attendance->notes,
            'reason' => 'Check-out',
            'sessions' => json_encode($sessions)
        ];
        Log::info('Updating attendance record for check-out', $attributes);
        $attendance->update($attributes);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-out recorded successfully.',
            'button_action' => 'checkin',
            'data' => $attendance,
            'worked_hours' => round($totalWorkedHours, 2),
        ]);
    }

    public function checkTodayStatus()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        Log::info('Checking today\'s attendance status', ['user_id' => $user->id, 'date' => $today]);

        // Check for approved leave
        $leaveToday = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($leaveToday) {
            Log::info('User is on approved leave', ['leave_id' => $leaveToday->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'You are on approved leave today.',
                'button_action' => 'none'
            ], 403);
        }

        // Check if today is Sunday
        if (Carbon::today()->isSunday()) {
            Log::info('Attendance not allowed on Sunday');
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance is not allowed on Sunday.',
                'button_action' => 'none'
            ], 403);
        }

        // Find today's attendance record
        $attendance = Attendance::where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            Log::info('No attendance record found for today');
            return response()->json([
                'status' => 'pending',
                'message' => 'You have not checked in yet today.',
                'button_action' => 'checkin'
            ]);
        }

        $sessions = is_string($attendance->sessions) ? json_decode($attendance->sessions, true) : $attendance->sessions;
        $sessions = is_array($sessions) ? $sessions : [];
        $totalWorkedHours = 0;

        // Calculate total worked hours
        if (!empty($sessions)) {
            foreach ($sessions as $session) {
                if (!empty($session['check_in']) && !empty($session['check_out'])) {
                    $totalWorkedHours += Carbon::parse($session['check_in'])->diffInMinutes($session['check_out']) / 60;
                }
            }
        }

        // Check if there is an active session (check_in but no check_out)
        $latestSession = !empty($sessions) ? end($sessions) : null;
        if ($attendance->check_in && !$attendance->check_out) {
            Log::info('Active session found', ['attendance_id' => $attendance->id, 'check_in' => $attendance->check_in]);
            return response()->json([
                'status' => 'checkجبن_done',
                'message' => 'You have checked in but not checked out yet.',
                'button_action' => 'checkout',
                'check_in_time' => $attendance->check_in,
                'worked_hours' => round($totalWorkedHours, 2),
                'attendance_id' => $attendance->id
            ]);
        }

        // Handle case where sessions are completed
        if ($latestSession && !empty($latestSession['check_in']) && !empty($latestSession['check_out'])) {
            Log::info('Session completed', ['attendance_id' => $attendance->id, 'check_out' => $latestSession['check_out']]);
            return response()->json([
                'status' => 'completed',
                'message' => 'You have checked out. Re-check-in if you return to work.',
                'button_action' => 'checkin',
                'check_in_time' => $attendance->check_in,
                'check_out_time' => $latestSession['check_out'],
                'worked_hours' => round($totalWorkedHours, 2),
                'attendance_id' => $attendance->id
            ]);
        }

        // Handle invalid or empty sessions
        Log::warning('Invalid or empty sessions for attendance record', [
            'attendance_id' => $attendance->id,
            'sessions' => $attendance->sessions
        ]);
        return response()->json([
            'status' => 'pending',
            'message' => 'Attendance record found but no valid sessions. Please check in again.',
            'button_action' => 'checkin',
            'attendance_id' => $attendance->id
        ]);
    }
}
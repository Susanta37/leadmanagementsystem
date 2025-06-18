<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\GeofenceSettings;
use App\Helpers\GeofenceHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
        $workingHours = $checkIn && $checkOut ? Carbon::parse($checkIn)->diffInMinutes($checkOut) / 60 : null;

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
                    'worked_hours' => $record->check_in && $record->check_out
                        ? round(Carbon::parse($record->check_in)->diffInMinutes($record->check_out) / 60, 2)
                        : null
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if today is Sunday
        if ($today->isSunday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendance is not allowed on Sunday.',
                'button_action' => 'none'
            ], 403);
        }

        // Check if already checked in
        $alreadyCheckedIn = Attendance::where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already checked in today.',
                'button_action' => 'checkout'
            ], 403);
        }

        // Check for approved leave
        $leaveToday = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($leaveToday) {
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
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'button_action' => 'checkin'
            ], 422);
        }

        // Get geofence settings
        $geofence = GeofenceSettings::first();
        if (!$geofence) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geofence settings not configured.',
                'button_action' => 'none'
            ], 500);
        }

      
        list($userLat, $userLon) = explode(',', $request->check_in_coordinates);

        // Check if user is within geofence
        if (!GeofenceHelper::isWithinGeofence($userLat, $userLon, $geofence->latitude, $geofence->longitude, $geofence->radius)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not within the office premises.',
                'button_action' => 'checkin'
            ], 403);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('checkin_image')) {
            $storedPath = $request->file('checkin_image')->store('attendance/checkin', 'public');
            $imagePath = '/storage/' . $storedPath;
        }

        // Create attendance record
        $attendance = Attendance::create([
            'employee_id' => $user->id,
            'date' => $today,
            'check_in' => now(),
            'check_in_location' => $request->check_in_location,
            'check_in_coordinates' => $request->check_in_coordinates,
            'checkin_image' => $imagePath,
            'notes' => $request->notes,
            'is_within_geofence' => true,
            'last_location_update' => now(),
            'reason' => 'Manual check-in'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in recorded successfully.',
            'button_action' => 'checkout',
            'data' => $attendance,
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

        // Get geofence settings
        $geofence = GeofenceSettings::first();
        if (!$geofence) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geofence settings not configured.',
                'button_action' => 'none'
            ], 500);
        }

        // Parse coordinates
        list($userLat, $userLon) = explode(',', $request->check_out_coordinates);
        $isWithinGeofence = GeofenceHelper::isWithinGeofence($userLat, $userLon, $geofence->latitude, $geofence->longitude, $geofence->radius);

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

        // Update attendance record
        $attendance->update([
            'check_out' => now(),
            'check_out_location' => $request->check_out_location,
            'check_out_coordinates' => $request->check_out_coordinates,
            'checkout_image' => $checkoutImagePath,
            'notes' => $request->notes ?? $attendance->notes,
            'is_within_geofence' => $isWithinGeofence,
            'last_location_update' => now(),
            'reason' => 'Manual check-out'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-out recorded successfully.',
            'button_action' => 'checkin',
            'data' => $attendance,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Validate request
        $validator = Validator::make($request->all(), [
            'current_coordinates' => 'required|string|regex:/^[-]?[0-9]{1,3}\.[0-9]{6},[-]?[0-9]{1,3}\.[0-9]{6}$/',
            'current_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'button_action' => 'none'
            ], 422);
        }

        // Find active attendance record
        $attendance = Attendance::where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active check-in found for today.',
                'button_action' => 'checkin'
            ], 404);
        }

        // Get geofence settings
        $geofence = GeofenceSettings::first();
        if (!$geofence) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geofence settings not configured.',
                'button_action' => 'none'
            ], 500);
        }

        // Parse coordinates
        list($userLat, $userLon) = explode(',', $request->current_coordinates);
        $isWithinGeofence = GeofenceHelper::isWithinGeofence($userLat, $userLon, $geofence->latitude, $geofence->longitude, $geofence->radius);

        if (!$isWithinGeofence) {
            // Auto-checkout if user is outside geofence
            $attendance->update([
                'check_out' => now(),
                'check_out_location' => $request->current_location,
                'check_out_coordinates' => $request->current_coordinates,
                'is_within_geofence' => false,
                'last_location_update' => now(),
                'notes' => ($attendance->notes ?? '') . "\nAuto-checked out due to leaving office premises.",
                'reason' => 'Auto-checked out due to leaving geofence'
            ]);

            return response()->json([
                'status' => 'completed',
                'message' => 'You have been checked out because you are out of the office area. Please check in after re-entering.',
                'button_action' => 'checkin',
                'check_in_time' => $attendance->check_in,
                'check_out_time' => $attendance->check_out,
                'worked_hours' => round(Carbon::parse($attendance->check_in)->diffInMinutes($attendance->check_out) / 60, 2),
            ]);
        }

        // Update location if still within geofence
        $attendance->update([
            'last_location_update' => now(),
            'is_within_geofence' => true,
            'check_in_location' => $request->current_location ?? $attendance->check_in_location,
            'check_in_coordinates' => $request->current_coordinates,
            'reason' => 'Location update'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated successfully.',
            'button_action' => 'checkout',
            'is_within_geofence' => true,
        ]);
    }

    public function checkTodayStatus()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Check for approved leave
        $leaveToday = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if ($leaveToday) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are on approved leave today.',
                'button_action' => 'none'
            ], 403);
        }

        // Check if today is Sunday
        if (Carbon::today()->isSunday()) {
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
            return response()->json([
                'status' => 'pending',
                'message' => 'You have not checked in yet today.',
                'button_action' => 'checkin'
            ]);
        }

        // Check for timeout (e.g., internet disconnection)
        if ($attendance->check_in && !$attendance->check_out) {
            $lastUpdate = Carbon::parse($attendance->last_location_update);
            if ($lastUpdate->diffInMinutes(now()) > 15) { // Timeout after 15 minutes
                $attendance->update([
                    'check_out' => now(),
                    'notes' => ($attendance->notes ?? '') . "\nAuto-checked out due to no location updates.",
                    'is_within_geofence' => false,
                    'reason' => 'Auto-checked out due to timeout'
                ]);
                return response()->json([
                    'status' => 'completed',
                    'message' => 'You have been checked out because you are out of the office area. Please check in after re-entering.',
                    'button_action' => 'checkin',
                    'check_in_time' => $attendance->check_in,
                    'check_out_time' => $attendance->check_out,
                    'worked_hours' => round(Carbon::parse($attendance->check_in)->diffInMinutes($attendance->check_out) / 60, 2),
                ]);
            }
        }

        // Check if checked in but not checked out
        if ($attendance->check_in && !$attendance->check_out) {
            return response()->json([
                'status' => 'checkin_done',
                'message' => $attendance->is_within_geofence
                    ? 'You have checked in but not checked out yet.'
                    : 'You are outside the office area. Please check out or return to the office.',
                'button_action' => 'checkout',
                'check_in_time' => $attendance->check_in,
                'is_within_geofence' => $attendance->is_within_geofence,
            ]);
        }

        // Check if both check-in and check-out are completed
        if ($attendance->check_in && $attendance->check_out) {
            $workedHours = round(Carbon::parse($attendance->check_in)->diffInMinutes($attendance->check_out) / 60, 2);

            return response()->json([
                'status' => 'completed',
                'message' => 'You have completed your attendance for today. Please check in again if you re-enter the office.',
                'button_action' => 'checkin',
                'check_in_time' => $attendance->check_in,
                'check_out_time' => $attendance->check_out,
                'worked_hours' => $workedHours,
            ]);
        }

        return response()->json([
            'status' => 'unknown',
            'message' => 'Something unexpected occurred.',
            'button_action' => 'none'
        ], 500);
    }

    public function getGeofenceSettings()
    {
        $geofence = GeofenceSettings::first();
        if (!$geofence) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geofence settings not configured.',
                'button_action' => 'none'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'office_name' => $geofence->office_name,
                'latitude' => $geofence->latitude,
                'longitude' => $geofence->longitude,
                'radius' => $geofence->radius,
            ],
            'button_action' => 'none'
        ]);
    }
}
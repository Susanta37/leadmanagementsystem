<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
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
    
    $validator = Validator::make($request->all(), [
        'checkin_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Actual file validation
        'check_in_location' => 'nullable|string|max:255',
        'check_in_coordinates' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);
    
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    }
    
    $imagePath = null;
     if ($request->hasFile('checkin_image')) {
        $storedPath = $request->file('checkin_image')->store('attendance/checkin', 'public');
        $imagePath = '/storage/' . $storedPath; 
    }
    
    $attendance = Attendance::create([
        'employee_id' => $user->id,
        'date' => today(),
        'check_in' => now(),
        'check_in_location' => $request->check_in_location,
        'check_in_coordinates' => $request->check_in_coordinates,
        'checkin_image' => $imagePath, // Store file path, not raw request data
        'notes' => $request->notes,
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Check-in recorded',
        'data' => $attendance,
    ]);
}

   public function update(Request $request, Attendance $attendance)
{
    $user = Auth::user();
    
    if ($attendance->employee_id !== $user->id) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }
    
    $validator = Validator::make($request->all(), [
        'check_out_location' => 'nullable|string|max:255',
        'check_out_coordinates' => 'nullable|string|max:255',
        'checkout_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Changed to handle file upload
        'notes' => 'nullable|string',
    ]);
    
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    }
    
    $checkoutImagePath = $attendance->checkout_image; // Keep existing image if no new one
    
    if ($request->hasFile('checkout_image')) {
        // Delete old checkout image if exists
        if ($attendance->checkout_image) {
            $oldPath = str_replace('/storage/', '', $attendance->checkout_image);
            Storage::disk('public')->delete($oldPath);
        }
        
        // Store new checkout image
        $storedPath = $request->file('checkout_image')->store('attendance/checkout', 'public');
        $checkoutImagePath = '/storage/' . $storedPath;
    }
    
    $attendance->update([
        'check_out' => now(),
        'check_out_location' => $request->check_out_location,
        'check_out_coordinates' => $request->check_out_coordinates,
        'checkout_image' => $checkoutImagePath,
        'notes' => $request->notes ?? $attendance->notes,
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Check-out updated',
        'data' => $attendance,
    ]);
}
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Mail\UserCredentials;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserCreateController extends Controller
{
    /**
     * Create a new user and send login credentials via email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Check if the authenticated user is authorized (e.g., team_lead)
        if (Auth::user()->designation !== 'team_lead') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to create users',
            ], 403);
        }

        // Log the raw input for debugging
        Log::info('UserCreateController::store Request', [
            'headers' => $request->headers->all(),
            'input' => $request->all(),
            'json' => $request->isJson() ? $request->json()->all() : 'Not JSON',
        ]);

        // Determine input type (JSON or form-data)
        $input = $request->isJson() ? $request->json()->all() : $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'sometimes|string|min:8',
            'phone' => 'nullable|string|max:255',
            'designation' => 'required|in:employee,team_lead,operations,admin',
            'department' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|string',
            'pan_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhar_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'team_lead_id' => 'nullable|exists:users,id,designation,team_lead',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate a random password if not provided
        $password = $input['password'] ?? Str::random(12);
        $hashedPassword = Hash::make($password);

    
        $profilePhotoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('profile_photos', 'public')
            : null;
        $panCardPath = $request->hasFile('pan_card')
            ? $request->file('pan_card')->store('pan_cards', 'public')
            : null;
        $aadharCardPath = $request->hasFile('aadhar_card')
            ? $request->file('aadhar_card')->store('aadhar_cards', 'public')
            : null;
        $signaturePath = $request->hasFile('signature')
            ? $request->file('signature')->store('signatures', 'public')
            : null;

       
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $hashedPassword,
            'phone' => $input['phone'] ?? null,
            'designation' => $input['designation'],
            'department' => $input['department'] ?? null,
            'profile_photo' => $profilePhotoPath,
            'address' => $input['address'] ?? null,
            'pan_card' => $panCardPath,
            'aadhar_card' => $aadharCardPath,
            'signature' => $signaturePath,
            'created_by' => Auth::id(),
            'team_lead_id' => $input['team_lead_id'] ?? null,
        ]);

        // Send email with credentials
        try {
            Mail::to($user->email)->send(new UserCredentials($user, $password));
        } catch (\Exception $e) {
            Log::error('Failed to send user credentials email: ' . $e->getMessage());
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully, but failed to send email',
                'data' => $user,
            ], 201);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully and credentials emailed',
            'data' => $user,
        ], 201);
    }
}
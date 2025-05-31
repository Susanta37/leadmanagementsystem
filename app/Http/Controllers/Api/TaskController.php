<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * List tasks for employee or team lead.
     * - Employee sees their own tasks.
     * - Team lead sees all tasks assigned by them, or their team.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->designation === 'team_lead') {
            // Team lead: see all tasks they assigned (by them), or for their team (users with their team_lead_id)
            $teamUserIds = User::where('team_lead_id', $user->id)->pluck('id')->toArray();
            $tasks = Task::with(['employee', 'teamLead'])
                ->where('team_lead_id', $user->id)
                ->orWhereIn('employee_id', $teamUserIds)
                ->get();
        } else {
            // Employee: see only their tasks
            $tasks = Task::with(['employee', 'teamLead'])
                ->where('employee_id', $user->id)
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks,
        ], 200);
    }

    /**
     * Store a new task.
     * Team lead can assign to individual (employee_id) or to whole team (no employee_id, will assign to all team members).
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user->designation !== 'team_lead') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only team leads can create tasks.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
            'priority' => 'nullable|string|in:low,medium,high',
            'assigned_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:assigned_date',
            'attachments' => 'nullable|string|max:1024',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Assign to all team members if employee_id not provided
        if (!$request->filled('employee_id')) {
            $teamMembers = User::where('team_lead_id', $user->id)->get();
            $tasks = [];
            foreach ($teamMembers as $member) {
                $task = Task::create([
                    'team_lead_id' => $user->id,
                    'employee_id' => $member->id,
                    'title' => $request->title,
                    'progress' => $request->progress ?? 0,
                    'priority' => $request->priority,
                    'assigned_date' => $request->assigned_date ?? now(),
                    'due_date' => $request->due_date,
                    'attachments' => $request->attachments,
                    'description' => $request->description,
                    'status' => $request->status,
                    'activity_timeline' => json_encode([
                        [
                            'timestamp' => now()->toDateTimeString(),
                            'action' => 'created',
                            'by' => $user->id,
                            'note' => 'Task assigned to employee'
                        ]
                    ]),
                ]);
                $tasks[] = $task;
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Task assigned to all team members.',
                'data' => $tasks,
            ], 201);
        }

        // Assign to one employee
        $task = Task::create([
            'team_lead_id' => $user->id,
            'employee_id' => $request->employee_id,
            'title' => $request->title,
            'progress' => $request->progress ?? 0,
            'priority' => $request->priority,
            'assigned_date' => $request->assigned_date ?? now(),
            'due_date' => $request->due_date,
            'attachments' => $request->attachments,
            'description' => $request->description,
            'status' => $request->status,
            'activity_timeline' => json_encode([
                [
                    'timestamp' => now()->toDateTimeString(),
                    'action' => 'created',
                    'by' => $user->id,
                    'note' => 'Task assigned'
                ]
            ]),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    /**
     * Show a single task.
     * Employee can see only their task. Team lead can see tasks they assigned.
     */
    public function show(Task $task): JsonResponse
    {
        $user = Auth::user();
        if (
            $user->designation !== 'team_lead' && $user->id !== $task->employee_id ||
            $user->designation === 'team_lead' && $user->id !== $task->team_lead_id
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to view this task.'
            ], 403);
        }

        $task->load(['employee', 'teamLead']);
        return response()->json([
            'status' => 'success',
            'message' => 'Task retrieved successfully.',
            'data' => $task,
        ], 200);
    }

    /**
     * Update a task.
     * Employee: can change progress, status, description.
     * Team lead: can edit all fields except employee_id if bulk.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $user = Auth::user();
        $updateFields = [];

        if ($user->designation === 'team_lead' && $user->id === $task->team_lead_id) {
            // Team lead can edit anything except employee_id unless bulk
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'progress' => 'sometimes|integer|min:0|max:100',
                'priority' => 'sometimes|string|in:low,medium,high',
                'assigned_date' => 'sometimes|date',
                'due_date' => 'sometimes|date|after_or_equal:assigned_date',
                'attachments' => 'sometimes|string|max:1024',
                'description' => 'sometimes|string',
                'status' => 'sometimes|string|in:pending,in_progress,completed',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $updateFields = $request->only([
                'title', 'progress', 'priority', 'assigned_date', 'due_date', 'attachments', 'description', 'status'
            ]);
        } elseif ($user->id === $task->employee_id) {
            // Employee can only update: progress, status, description
            $validator = Validator::make($request->all(), [
                'progress' => 'sometimes|integer|min:0|max:100',
                'description' => 'sometimes|string',
                'status' => 'sometimes|string|in:pending,in_progress,completed',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $updateFields = $request->only(['progress', 'description', 'status']);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update this task.',
            ], 403);
        }

        // Update timeline
        $timeline = json_decode($task->activity_timeline, true) ?: [];
        $timeline[] = [
            'timestamp' => now()->toDateTimeString(),
            'action' => 'updated',
            'by' => $user->id,
            'fields_changed' => array_keys($updateFields),
            'note' => 'Task updated'
        ];
        $updateFields['activity_timeline'] = json_encode($timeline);

        $task->update($updateFields);
        $task->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully.',
            'data' => $task,
        ], 200);
    }

    /**
     * Delete a task (team lead only).
     */
    public function destroy(Task $task): JsonResponse
    {
        $user = Auth::user();
        if ($user->designation !== 'team_lead' || $user->id !== $task->team_lead_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only the team lead can delete this task.',
            ], 403);
        }
        $task->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully.',
        ], 200);
    }
}
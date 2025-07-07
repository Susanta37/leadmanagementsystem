<?php
namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'id' => 'nullable|exists:tasks,id', // ✅ Allow optional task ID for editing
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'assigned_date' => 'required|date',
        'due_date' => 'required|date|after:assigned_date',
        'priority' => 'required|in:low,medium,high,urgent',
        'progress' => 'required|integer|min:0|max:100',
        'target_type' => 'required|in:individual,all',
        'employees' => 'required_if:target_type,individual|array',
        'employees.*' => 'exists:users,id',
        'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:2048',
    ]);

    try {
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }


        // ✅ Either update or create new
        $task = $request->id
            ? Task::findOrFail($request->id)
            : new Task();

        $task->team_lead_id = auth()->id();
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->assigned_date = $validated['assigned_date'];
        $task->due_date = $validated['due_date'];
       $task->priority = $validated['priority'];
        $task->progress = $validated['progress'];
        $task->status = 'pending';
        $task->target_type = $validated['target_type'];

        // If new attachments uploaded, overwrite (or you can merge)
        if (!empty($attachmentPaths)) {
            $task->attachments = json_encode($attachmentPaths);
        }

        $task->save();

        // 🔁 Optional: Remove existing notifications if updating
        if ($request->id) {
            Notification::where('task_id', $task->id)->delete();
        }

        // Determine assignees
        $employeeIds = $validated['target_type'] === 'all'
            ? User::where('team_lead_id', auth()->id())->pluck('id')->toArray()
            : $validated['employees'];

        if (empty($employeeIds)) {
            return response()->json(['success' => false, 'message' => 'No employees available for assignment'], 422);
        }

        foreach ($employeeIds as $empId) {
            Notification::create([
                'user_id' => $empId,
                'lead_id' => auth()->id(),
                'task_id' => $task->id,
                'message' => 'You have been assigned a task: ' . $task->title,
                'is_read' => false,
            ]);
        }

        $action = $request->id ? 'updated' : 'created';
        return response()->json(['success' => true, 'message' => "Task {$action} successfully"]);

    } catch (Exception $e) {
        Log::error('Task save failed: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Task save failed'], 500);
    }
}


 public function list()
{
    $leadId = auth()->id();

    $tasks = Task::where('team_lead_id', $leadId)
        ->with(['assignees:id,name,avatar']) // define relation first
        ->get();

    return response()->json($tasks);
}

public function getAllTasksForTeamLead()
{
    $leadId = auth()->id();
    Log::info('Fetching tasks for team lead ID: ' . $leadId);

    try {
        $tasks = Task::with(['assignees:id,name,profile_photo'])
            ->where('team_lead_id', $leadId)
            ->get();

        return response()->json($tasks);
    } catch (Exception $e) {
        Log::error('getAllTasksForTeamLead failed: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}






}

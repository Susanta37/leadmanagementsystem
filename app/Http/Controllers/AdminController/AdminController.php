<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::latest()->take(5)->get();
        $leads = Lead::latest()->take(5)->get();
        $tasks = Task::latest()->take(5)->get();
        $attendances = Attendance::latest()->take(5)->get();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return view('admin.dashboard', compact('users', 'leads', 'tasks', 'attendances', 'notifications'));
    }

    // public function indexUsers()
    // {
    //     $users = User::paginate(10);
    //     return view('admin.users.index', compact('users'));
    // }

    // public function createUser()
    // {
    //     return view('admin.users.create');
    // }

    // public function storeUser(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:8|confirmed',
    //         'phone' => 'required|string|max:20',
    //         'designation' => 'required|in:employee,team_lead,operations,admin',
    //         'department' => 'required|string|max:255',
    //         'profile_photo' => 'nullable|image|max:2048',
    //         'address' => 'nullable|string',
    //         'pan_card' => 'nullable|string|max:255',
    //         'aadhar_card' => 'nullable|string|max:255',
    //         'signature' => 'nullable|image|max:2048',
    //         'team_lead_id' => 'nullable|exists:users,id',
    //     ]);

    //     $data = $validated + ['created_by' => Auth::id()];

    //     if ($request->hasFile('profile_photo')) {
    //         $data['profile_photo'] = $request->file('profile_photo')->store('photos', 'public');
    //     }
    //     if ($request->hasFile('signature')) {
    //         $data['signature'] = $request->file('signature')->store('signatures', 'public');
    //     }

    //     User::create($data);

    //     return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    // }

    // public function editUser(User $user)
    // {
    //     return view('admin.users.edit', compact('user'));
    // }

    // public function updateUser(Request $request, User $user)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'phone' => 'required|string|max:20',
    //         'designation' => 'required|in:employee,team_lead,operations,admin',
    //         'department' => 'required|string|max:255',
    //         'profile_photo' => 'nullable|image|max:2048',
    //         'address' => 'nullable|string',
    //         'pan_card' => 'nullable|string|max:255',
    //         'aadhar_card' => 'nullable|string|max:255',
    //         'signature' => 'nullable|image|max:2048',
    //         'team_lead_id' => 'nullable|exists:users,id',
    //     ]);

    //     $data = $validated;

    //     if ($request->hasFile('profile_photo')) {
    //         $data['profile_photo'] = $request->file('profile_photo')->store('photos', 'public');
    //     }
    //     if ($request->hasFile('signature')) {
    //         $data['signature'] = $request->file('signature')->store('signatures', 'public');
    //     }

    //     $user->update($data);

    //     return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    // }

    // public function destroyUser(User $user)
    // {
    //     if ($user->id === Auth::id()) {
    //         abort(403, 'Cannot delete your own account.');
    //     }
    //     $user->delete();
    //     return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    // }

    // public function indexLeads()
    // {
    //     $leads = Lead::paginate(10);
    //     return view('admin.leads.index', compact('leads'));
    // }

    // public function indexTasks()
    // {
    //     $tasks = Task::paginate(10);
    //     return view('admin.tasks.index', compact('tasks'));
    // }

    // public function indexAttendances()
    // {
    //     $attendances = Attendance::paginate(10);
    //     return view('admin.attendances.index', compact('attendances'));
    // }

    // public function indexNotifications()
    // {
    //     $notifications = Notification::paginate(10);
    //     return view('admin.notifications.index', compact('notifications'));
    // }
}

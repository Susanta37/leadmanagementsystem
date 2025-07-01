<?php

namespace App\Http\Controllers\TLController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentials;
use App\Models\User;

class TLEmployeeController extends Controller
{
    /**
     * Display a listing of the team lead's employees.
     */


    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'designation'    => 'required|string',
            'phone'          => 'required|string',
            'employee_role'  => 'nullable|string',
            'address'        => 'nullable|string',
            'profile_photo'  => 'nullable|image|max:2048',
        ]);

        $plainPassword = Str::random(10);

        $user = new User();
        $user->name           = $request->name;
        $user->email          = $request->email;
        $user->designation    = $request->designation;
        $user->phone          = $request->phone;
        $user->employee_role  = $request->employee_role;
        $user->address        = $request->address ?? '';
        $user->password       = Hash::make($plainPassword);
        $user->created_by     = auth()->id();
        $user->team_lead_id   = auth()->id();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_photos'), $filename);
            $user->profile_photo = 'uploads/profile_photos/' . $filename;
        }

        $user->save();

        // Send welcome email
        Mail::to($user->email)->send(new UserCredentials($user, $plainPassword));

        return redirect()->back()->with('success', 'Employee added successfully and credentials sent to email.');
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $id,
            'designation'    => 'required|string',
            'phone'          => 'required|string',
            'employee_role'  => 'nullable|string',
            'address'        => 'nullable|string',
            'profile_photo'  => 'nullable|image|max:2048',
        ]);

        $user->name           = $request->name;
        $user->email          = $request->email;
        $user->designation    = $request->designation;
        $user->phone          = $request->phone;
        $user->employee_role  = $request->employee_role;
        $user->address        = $request->address ?? '';

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_photos'), $filename);
            $user->profile_photo = 'uploads/profile_photos/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Employee updated successfully.');
    }


//     public function edit($id)
// {
//     $employee = User::where('id', $id)
//                     ->where('team_lead_id', auth()->id()) // ensure only that TL can edit
//                     ->firstOrFail();

//     return view('TeamLead.Employees.edit', compact('employee'));
// }

    /**
     * Remove the specified employee.
     */
    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();

    //     return redirect()->back()->with('success', 'Employee deleted successfully.');
    // }

    // Deactivate (Soft delete)
public function deactivate($id)
{
    $user = User::where('team_lead_id', auth()->id())->findOrFail($id);
    $user->delete();

    return redirect()->back()->with('success', 'Employee deactivated (soft deleted) successfully.');
}

// Activate (Restore)
public function activate($id)
{
    $user = User::withTrashed()
        ->where('team_lead_id', auth()->id())
        ->findOrFail($id);

    $user->restore();

    return redirect()->back()->with('success', 'Employee restored successfully.');
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Super admin can see all users
            $users = User::with('company')->paginate(15);
        } else {
            // Company admin can only see users from their company
            $users = User::where('company_id', $user->company_id)->with('company')->paginate(15);
        }

        return view('backend.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Super admin can assign users to any company
            $companies = Company::where('status', 'active')->get();
        } else {
            // Company admin can only assign users to their company
            $companies = Company::where('id', $user->company_id)->where('status', 'active')->get();
        }

        // Available roles (excluding super-admin)
        $roles = [
            'company-admin' => 'Company Admin',
            'admin' => 'Admin',
            'user' => 'User',
            'editor' => 'Editor',
            'creator' => 'Creator'
        ];

        return view('backend.user.create', compact('companies', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:company-admin,admin,user,editor,creator',
            'company_id' => 'required|exists:companies,id',
        ]);

        // Check if user can assign to this company
        if (!$user->canManageCompany($request->company_id)) {
            return redirect()->back()->with('error', 'You are not authorized to assign users to this company.');
        }

        // Check if user can assign this role
        if ($request->role === 'company-admin' && !$user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only super admin can create company admin users.');
        }

        User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->canManageCompany($user->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return view('backend.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->canManageCompany($user->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($currentUser->isSuperAdmin()) {
            // Super admin can assign users to any company
            $companies = Company::where('status', 'active')->get();
        } else {
            // Company admin can only assign users to their company
            $companies = Company::where('id', $currentUser->company_id)->where('status', 'active')->get();
        }

        // Available roles (excluding super-admin)
        $roles = [
            'company-admin' => 'Company Admin',
            'admin' => 'Admin',
            'user' => 'User',
            'editor' => 'Editor',
            'creator' => 'Creator'
        ];

        return view('backend.user.edit', compact('user', 'companies', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->canManageCompany($user->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:company-admin,admin,user,editor,creator',
            'company_id' => 'required|exists:companies,id',
        ]);

        // Check if user can assign to this company
        if (!$currentUser->canManageCompany($request->company_id)) {
            return redirect()->back()->with('error', 'You are not authorized to assign users to this company.');
        }

        // Check if user can assign this role
        if ($request->role === 'company-admin' && !$currentUser->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Only super admin can create company admin users.');
        }

        // Prevent user from changing their own role to avoid locking themselves out
        if ($user->id === $currentUser->id && $request->role !== $currentUser->role) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $user->update([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->canManageCompany($user->company_id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Prevent user from deleting themselves
        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deletion of super admin
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Super admin users cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }
}

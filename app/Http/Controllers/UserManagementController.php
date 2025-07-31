<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedCompanyId = $request->get('company_id');
        $companies = $user->isSuperAdmin()
            ? Company::all()
            : $user->companies;
        $users = collect();
        if ($selectedCompanyId) {
            $users = User::whereHas('companies', function ($q) use ($selectedCompanyId) {
                $q->where('companies.id', $selectedCompanyId);
            })->where('role', '!=', 'super-admin')->with('companies')->paginate(15);
        }
        // Unassigned users: users with no companies, not super-admin
        $unassignedQuery = User::whereDoesntHave('companies')
            ->where('role', '!=', 'super-admin')
            ->with('profile');
        if ($search = $request->get('search_name')) {
            $unassignedQuery->where('name', 'like', "%$search%");
        }
        if ($search = $request->get('search_user_name')) {
            $unassignedQuery->where('user_name', 'like', "%$search%");
        }
        if ($search = $request->get('search_email')) {
            $unassignedQuery->where('email', 'like', "%$search%");
        }
        // Profile fields
        $profileFields = [
            'gender',
            'religion',
            'city',
        ];
        foreach ($profileFields as $field) {
            if ($search = $request->get('search_' . $field)) {
                $unassignedQuery->whereHas('profile', function ($q) use ($field, $search) {
                    $q->where($field, 'like', "%$search%");
                });
            }
        }
        $unassignedUsers = $unassignedQuery->paginate(30)->appends($request->except('page'));
        return view('backend.user.index', compact('users', 'companies', 'selectedCompanyId', 'unassignedUsers'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $companies = Company::where('status', 'active')->get();
        } else {
            $companies = $user->companies()->where('status', 'active')->get();
        }

        $roles = [
            'user' => 'User',
            // 'editor' => 'Editor',
            // 'creator' => 'Creator'
        ];

        if (Auth::user()->isSuperAdmin()) {
            // Push 'admin' to the beginning of the array
            $roles = ['admin' => 'Admin'] + $roles;
        }

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
            'company_roles' => 'required|array',
        ]);

        $newUser = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Sync company roles
            $syncData = [];
            foreach ($request->company_roles as $companyId => $role) {
                if ($role) {
                    $syncData[$companyId] = ['role' => $role];
                }
            }
            $user->companies()->sync($syncData);

            Profile::create([
                'user_id' => $user->id,
            ]);
            return $user;
        });

        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $currentUser = Auth::user();

        // if (!$currentUser->canManageCompany($user->company_id)) {
        //     return redirect()->back()->with('error', 'Unauthorized access.');
        // }

        $user = User::findOrFail($id);

        return view('backend.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if (!$currentUser->canManageCompany($user->company_id) && !$currentUser->id) {
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
            'admin' => 'Admin',
            'user' => 'User',
            // 'editor' => 'Editor',
            // 'creator' => 'Creator'
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
            'user_name' => ['string','max:255','unique:users,user_name,'. $user->id],
            'email' => 'email|unique:users,email,' . $user->id,
            'company_roles' => 'required|array',
        ]);

        $user->update([
            'name' => $request->name ,
            'user_name' => $request->user_name ?? $user->user_name,
            'email' => $request->email ?? $user->email,
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

        // Sync company roles
        $syncData = [];
        foreach ($request->company_roles as $companyId => $role) {
            if ($role) {
                $syncData[$companyId] = ['role' => $role];
            }
        }
        $user->companies()->sync($syncData);

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

    public function unassigned(Request $request)
    {
        $companyId = $request->get('company_id');
        $unassignedQuery = \App\Models\User::where('role', '!=', 'super-admin')->with('profile');
        if ($companyId) {
            // Users not assigned to this company (but may be assigned to others)
            $unassignedQuery->whereDoesntHave('companies', function ($q) use ($companyId) {
                $q->where('companies.id', $companyId);
            });
        } else {
            // Users with no companies at all
            $unassignedQuery->whereDoesntHave('companies');
        }
        if ($search = $request->get('search_name')) {
            $unassignedQuery->where('name', 'like', "%$search%");
        }
        if ($search = $request->get('search_user_name')) {
            $unassignedQuery->where('user_name', 'like', "%$search%");
        }
        if ($search = $request->get('search_email')) {
            $unassignedQuery->where('email', 'like', "%$search%");
        }
        $profileFields = [
            'gender',
            'religion',
            'city',
        ];
        foreach ($profileFields as $field) {
            if ($search = $request->get('search_' . $field)) {
                $unassignedQuery->whereHas('profile', function ($q) use ($field, $search) {
                    $q->where($field, 'like', "%$search%");
                });
            }
        }
        $unassignedUsers = $unassignedQuery->paginate(30)->appends($request->except('page'));
        return view('backend.user.unassigned', compact('unassignedUsers', 'companyId'));
    }
}

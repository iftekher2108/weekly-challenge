<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $companies = Company::withCount('users')->paginate(10);
        } else {
            // Company admins can only see their own company
            $companies = Company::whereIn('id', $user->companies()->pluck('companies.id'))->withCount('users')->paginate(10);
        }

        return view('backend.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company
     */
    public function create()
    {
        // Only super admin can create companies
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Get all users who are not super-admins
        $adminUsers = User::where('role', '!=', 'super-admin')->get();

        return view('backend.company.create', compact('adminUsers'));
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        // Only super admin can create companies
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admin_user_ids' => 'nullable|array|min:1',
            'admin_user_ids.*' => 'exists:users,id',
        ]);

        // Create company
        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $company->update(['logo' => $logoPath]);
        }

        // Attach all selected admins as admins (formerly company-admins)
        $adminSync = [];
        $company_ids = [];
        if ($request->admin_user_ids) {
            foreach ($request->admin_user_ids as $adminId) {
                $adminSync[$adminId] = ['role' => 'admin'];
                $user = User::find($adminId);
                // 👇 Properly merge flat values from user->company_id
                $company_ids = array_merge($company_ids, explode(',', $user->company_id));
            }
            $company_ids[] = $company->id;
            // 🧹 Remove duplicates and reset array keys
            $company_ids = array_values(array_unique($company_ids));

            // ✅ Convert final array to string (for DB update)
            $company_id_string = implode(',', $company_ids);
            User::whereIn('id', array_keys($adminSync))->update(['company_id' => $company_id_string]);
            $company->users()->attach($adminSync);
        }

        return redirect()->route('admin.company.index')->with('success', 'Company created successfully with admin user(s).');
    }

    /**
     * Display the specified company
     */
    public function show(Company $company)
    {
        $user = Auth::user();

        if (!$user->canManageCompany($company->id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $company->load(['users', 'admins', 'employees']);

        return view('backend.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company
     */
    public function edit(Company $company)
    {
        $user = Auth::user();

        if (!$user->canManageCompany($company->id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Get all users who are not super-admins
        $adminUsers = User::where('role', '!=', 'super-admin')->get();

        return view('backend.company.edit', compact('company', 'adminUsers'));
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company)
    {
        $user = Auth::user();

        if (!$user->canManageCompany($company->id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:active,inactive',
            'admin_user_ids' => 'nullable|array|min:1',
            'admin_user_ids.*' => 'exists:users,id',
        ]);
        $company->update([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status ? $request->status : $company->status,
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $company->update(['logo' => $logoPath]);
        }

        // Sync admins: set selected users as admin, others as user
        $currentAdmins = $company->admins()->pluck('users.id')->toArray();

        $company_ids = [ explode(',',$company->id)];

        $adminSync = [];
        if ($request->admin_user_ids) {
            foreach ($request->admin_user_ids as $adminId) {
                $adminSync[$adminId] = ['role' => 'admin'];
                $user = User::find($adminId);
                // 👇 Properly merge flat values from user->company_id
                $company_ids = array_merge($company_ids, explode(',', $user->company_id));
            }
            $company_ids[] = $company->id;
            // 🧹 Remove duplicates and reset array keys
            $company_ids = array_values(array_unique($company_ids));

            // ✅ Convert final array to string (for DB update)
            $company_id_string = implode(',', $company_ids);
            User::whereIn('id', array_keys($adminSync))->update(['company_id' => $company_id_string]);
            $company->users()->attach($adminSync);
        }

        // Set previous admins not in new list to 'user'
        $otherUsers = array_diff($currentAdmins, $adminSync);
        foreach ($otherUsers as $userId) {
            $syncData[$userId] = ['role' => 'user'];
        }
        $company->users()->syncWithoutDetaching($syncData);

        return redirect()->route('admin.company.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company)
    {
        // Only super admin can delete companies
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Delete company logo
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->route('admin.company.index')->with('success', 'Company deleted successfully.');
    }

    /**
     * Show company users
     */
    public function users(Company $company)
    {
        $user = Auth::user();

        if (!$user->canManageCompany($company->id)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $users = $company->users()->paginate(10);

        return view('backend.company.users', compact('company', 'users'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            $companies = Company::where('id', $user->company_id)->withCount('users')->paginate(10);
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

        return view('backend.company.create');
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
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_username' => 'required|string|unique:users,user_name',
            'admin_password' => 'required|string|min:8',
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

        // Create company admin
        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'user_name' => $request->admin_username,
            'password' => Hash::make($request->admin_password),
            'role' => 'company-admin',
            'company_id' => $company->id,
        ]);

        return redirect()->route('admin.company.index')->with('success', 'Company created successfully with admin user.');
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

        return view('backend.company.edit', compact('company'));
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
            'status' => 'required|in:active,inactive',
        ]);

        $company->update([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $company->update(['logo' => $logoPath]);
        }

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

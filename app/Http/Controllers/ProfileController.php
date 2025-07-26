<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $data = $request->validate([
            'dob' => 'nullable|date',
            'mobile' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'blood' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:255',
            'nid' => 'nullable|string|max:255',
            'bid' => 'nullable|string|max:255',
        ]);

        $profile->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}

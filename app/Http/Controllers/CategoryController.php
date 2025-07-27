<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companyId = $isSuperAdmin ? request()->get('company_id') : $user->companies()->first()->id;
        $categories = Category::where('company_id', $companyId)->with(['children', 'parent'])->get();
        return view('backend.catagory.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $request->validate([
            'parent_id' => 'nullable|integer',
            'picture' => 'image|mimes:png,jpg|nullable|max:2028',
            'title' => 'string|required|max:30',
            'description' => 'string|nullable|max:250',
            'company_id' => $isSuperAdmin ? 'required|exists:companies,id' : '',
        ]);
        $companyId = $isSuperAdmin ? $request->company_id : $user->companies()->first()->id;
        if (!$isSuperAdmin && !$user->canManageCompany($companyId)) {
            abort(403, 'Unauthorized');
        }
        DB::transaction(function () use ($request, $companyId) {
            $picture = null;
            if ($request->hasFile('picture')) {
                $dirname = 'category';
                $filename = 'cat_' . time() . '_' . Date('d-M-Y') . '.' . $request->file('picture')->extension();
                $request->file('picture')->storeAs($dirname, $filename, 'public');
                $picture = $filename;
            }
            Category::create([
                'user_id' => $request->user_id,
                'picture' => $picture,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'description' => $request->description,
                'company_id' => $companyId,
            ]);
        });
        return redirect()->route('admin.category')->with('success', 'Category added Successfully');
    }


    public function edit($id)
    {
        $categories = Category::Where('user_id', '=', Auth::user()->id)->with(['children', 'parent'])->get();
        $category = Category::findOrFail($id);
        return view('backend.catagory.edit', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $category = Category::findOrFail($id);
        $companyId = $isSuperAdmin ? $request->company_id : $user->companies()->first()->id;
        if (!$isSuperAdmin && !$user->canManageCompany($companyId)) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'parent_id' => 'nullable|integer',
            'picture' => 'image|mimes:png,jpg|nullable|max:2028',
            'title' => 'string|required|max:30',
            'description' => 'string|nullable|max:250',
            'company_id' => $isSuperAdmin ? 'required|exists:companies,id' : '',
        ]);
        DB::transaction(function () use ($request, $category, $companyId) {
            $picture = $category->picture;
            if ($request->hasFile('picture')) {
                if (Storage::disk('public')->exists('category/' . $category->picture)) {
                    Storage::disk('public')->delete('category/' . $category->picture);
                }
                $dirname = 'category';
                $filename = 'cat_' . time() . '_' . Date('d-M-Y') . '.' . $request->file('picture')->extension();
                $request->file('picture')->storeAs($dirname, $filename, 'public');
                $picture = $filename;
            }
            $category->update([
                'user_id' => $request->user_id,
                'picture' => $picture,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'description' => $request->description,
                'company_id' => $companyId,
            ]);
        });
        return redirect()->route('admin.category')->with('success', value: 'Category update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $category = Category::findOrFail($id);
        if (Storage::disk('public')->exists('category/' . $category->picture)) {
            Storage::disk('public')->delete('category/' . $category->picture);
        }
        $category->delete();
        return redirect()->route('admin.category')->with('success', "category Delete Successfully");
    }
}

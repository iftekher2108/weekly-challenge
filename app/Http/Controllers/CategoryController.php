<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function companySearch() {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? Company::where('status','active')->paginate(10) : $user->companies()->paginate(10);
        return view('backend.catagory.companySearch', compact('companies'));
     }


    public function index($id)
    {
        $company = Company::findOrFail($id);
        $categories = Category::where('company_id', $company->id)->with(['children', 'parent'])->get();
        return view('backend.catagory.index', compact('categories','company'));
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
        $companyId = $request->company_id ;
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
        return redirect()->route('admin.company.category',$companyId)->with('success', 'Category added Successfully');
    }


    public function edit($com_id, $id)
    {
        $categories = Category::where('company_id',$com_id)->with(['children', 'parent'])->get();
        $category = Category::findOrFail($id);
        $company = Company::find($com_id);
        return view('backend.catagory.edit', compact('categories', 'category','company'));
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
        return redirect()->route('admin.company.category',$companyId)->with('success', value: 'Category update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, $id)
    {

        $category = Category::findOrFail($id);
        if (Storage::disk('public')->exists('category/' . $category->picture)) {
            Storage::disk('public')->delete('category/' . $category->picture);
        }
        $category->delete();
        $companyId = $request->company_id;
        return redirect()->route('admin.company.category',$companyId)->with('success', "category Delete Successfully");
    }
}

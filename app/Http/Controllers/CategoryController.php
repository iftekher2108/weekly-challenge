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
        $categories = Category::Where('user_id','=',Auth::user()->id)->with(['children','parent'])->get();
        return view('backend.catagory.index',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|integer',
            'picture' => 'image|mimes:png,jpg|nullable|max:2028',
            'title' => 'string|required|max:30',
            'description' => 'string|nullable|max:250'
        ]);

        DB::transaction(function() use($request) {
        $picture = null;
        if($request->hasFile('picture')) {
            $dirname = 'category';
            $filename = 'cat_'.time().'_'. Date('d-M-Y').'.'. $request->file('picture')->extension();
           $request->file('picture')->storeAs($dirname,$filename,'public');
            $picture = $filename;
        }
            Category::create([
                'user_id' => $request->user_id,
                'picture' => $picture,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'description' => $request->description,
            ]);
        });

        return redirect()->route('admin.category')->with('success','Category added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $category = Category::findOrFail($id);
        if(Storage::disk('public')->exists('category/'. $category->picture)) {
            Storage::disk('public')->delete('category/'. $category->picture);
        }
        $category->delete();
        return redirect()->route('admin.category')->with('success',"category Delete Successfully");
    }
}

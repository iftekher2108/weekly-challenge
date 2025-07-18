<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = task::query();

       $categories = Category::Where('user_id','=',Auth::user()->id)->with(['children','parent'])->get();


       if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title','like',"%".$search."%");
        }
       $tasks = $query->paginate(35);
        return view('backend.task.index',compact('tasks','categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          $request->validate([
            'cat_id' => 'required',
            'user_id' => 'required',
            'picture' => 'image|mimes:png,jpg|nullable|max:2028',
            'title' => 'string|required|max:30',
            'description' => 'string|nullable|max:250'
        ]);

        DB::transaction(function() use($request) {
        $picture = null;
        if($request->hasFile('picture')) {
            $dirname = 'task-list';
            $filename = 'task-list_'.time().'_'. Date('d-M-Y').'.'. $request->file('picture')->extension();
           $request->file('picture')->storeAs($dirname,$filename,'public');
            $picture = $filename;
        }
            task::create([
                'user_id' => $request->user_id,
                'cat_id' => $request->cat_id,
                'picture' => $picture,
                'title' => $request->title,
                'description' => $request->description,
            ]);
        });

        return redirect()->route('admin.task')->with('success','Task added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(task $task)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\taskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = taskList::query();

       if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title','like',"%".$search."%");
        }
       $taskLists = $query->paginate(35);
        return view('backend.task-list.index',compact( 'taskLists'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
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
            taskList::create([
                'picture' => $picture,
                'title' => $request->title,
                'description' => $request->description,
            ]);
        });

        return redirect()->route('admin.taskList')->with('success','Category added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(taskList $taskList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(taskList $taskList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, taskList $taskList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $taskList = taskList::findOrFail($id);
        if(Storage::disk('public')->exists('task-list/'. $taskList->picture)) {
            Storage::disk('public')->delete('task-list/'. $taskList->picture);
        }
        $taskList->delete();
        return redirect()->route('admin.taskList')->with('success',"Task-List Delete Successfully");
    }
}

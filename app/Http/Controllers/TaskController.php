<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = task::query();

        $categories = Category::Where('user_id', '=', Auth::user()->id)->with(['children', 'parent'])->get();


        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', "%" . $search . "%");
        }
        $tasks = $query->paginate(35);
        return view('backend.task.index', compact('tasks', 'categories'));
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

        DB::transaction(function () use ($request) {
            $picture = null;
            if ($request->hasFile('picture')) {
                $dirname = 'task';
                $filename = 'task_' . time() . '_' . Date('d-M-Y') . '.' . $request->file('picture')->extension();
                $request->file('picture')->storeAs($dirname, $filename, 'public');
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

        return redirect()->route('admin.task')->with('success', 'Task added Successfully');
    }


    public function edit($id)
    {
        $task = task::findOrFail($id);
        $categories = Category::Where('user_id', '=', Auth::user()->id)->with(['children', 'parent'])->get();

        return view('backend.task.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cat_id' => 'required',
            'user_id' => 'required',
            'picture' => 'image|mimes:png,jpg|nullable|max:2028',
            'title' => 'string|required|max:30',
            'description' => 'string|nullable|max:250'
        ]);
        $task = task::findOrFail($id);
        DB::transaction(function () use ($request, $task) {
            $picture = $task->picture;
            if ($request->hasFile('picture')) {
                if (Storage::disk('public')->exists('task/' . $task->picture)) {
                    Storage::disk('public')->delete('task/' . $task->picture);
                }
                $dirname = 'task';
                $filename = 'task_' . time() . '_' . Date('d-M-Y') . '.' . $request->file('picture')->extension();
                $request->file('picture')->storeAs($dirname, $filename, 'public');
                $picture = $filename;
            }

            $task->update([
                'user_id' => $request->user_id,
                'cat_id' => $request->cat_id,
                'picture' => $picture,
                'title' => $request->title,
                'description' => $request->description,
            ]);
        });

        return redirect()->route('admin.task')->with('success', value: 'Task update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $task = task::findOrFail($id);
        if (Storage::disk('public')->exists('task/' . $task->picture)) {
            Storage::disk('public')->delete('task/' . $task->picture);
        }
        $task->delete();
        return redirect()->route('admin.task')->with('success', 'Task delete Successfully');
    }
}

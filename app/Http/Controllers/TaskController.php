<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\Category;
use Carbon\Carbon;
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
        $weeklyTasks = Category::with(['children.task' => function ($query) {
            $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->orderBy('progress', 'desc');
        }, 'task' => function ($query) {
            $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->orderBy('progress', 'desc');
        }])
            ->where('user_id', Auth::user()->id)
            ->get();

        $weeklyTasks->transform(function ($cat) {
            $tasks = $cat->task;
            $cat->overall_progress = $tasks->count()
                ? round($tasks->sum('progress') / $tasks->count())
                : 0;
            return $cat;
        });

        $categories = Category::Where('user_id',  '=', Auth::user()->id)->with(['children', 'parent'])->get();

        return view('backend.task.index', compact('weeklyTasks', 'categories'));
    }


    // public function completed()
    // {

    //     $weeklyTasksCompleted = Category::with(relations: ['children.task' => function ($query) {
    //         $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
    //             ->where('status', '=', 'progress')
    //             ->orderBy('progress', 'desc');
    //     }, 'task' => function ($query) {
    //         $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
    //             ->where('status', '=', 'completed')
    //             ->orderBy('progress', 'desc');
    //     }])
    //         ->where('user_id', Auth::user()->id)
    //         ->get();
    //     return view('backend.task.completed', compact('weeklyTasksCompleted'));
    // }

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
            'description' => 'string|nullable|max:250',
            'due_date' => 'required',
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
                'due_date' => Carbon::parse($request->due_date)->format('Y-m-d'),
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
            'description' => 'string|nullable|max:250',
            'due_date' => 'required',
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
                'due_date' => Carbon::parse($request->due_date)->format('Y-m-d'),

            ]);
        });

        return redirect()->route('admin.task')->with('success', value: 'Task update Successfully');
    }


    public function taskProgress(Request $request, $id)
    {
        $task = task::findOrFail($id);
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        if ($request->progress == 100) {
            $task->update(['status' => 'completed']);
        } else {
            $task->update(['status' => 'progress']);
        }

        if ($request->has('due_date')) {
            $task->update(['due_date' => Carbon::parse($request->due_date)->format('Y-m-d')]);
        }

        $task->update(['progress' => $request->progress]);

        return redirect()->route('admin.task')->with('success', 'Task progress updated Successfully');
    }

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

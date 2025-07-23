<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: current month
        $start = $request->input('start', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfMonth()->toDateString());
        $period = $request->input('period', 'month');

        if ($period === 'week') {
            $start = Carbon::now()->startOfWeek()->toDateString();
            $end = Carbon::now()->endOfWeek()->toDateString();
        } elseif ($period === 'range' && $request->filled(['start', 'end'])) {
            // Use provided start and end
        } else {
            $start = Carbon::now()->startOfMonth()->toDateString();
            $end = Carbon::now()->endOfMonth()->toDateString();
        }

        $tasks = task::whereBetween('due_date', [$start, $end])
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->get();
        $tasksCreated = $tasks->count();
        $tasksCompleted = $tasks->where('status', 'completed')->count();
        $tasksInProgress = $tasks->where('status', 'progress')->count();
        $tasksNotCompleted = $tasks->where('status', '!=', 'completed')->count();

        // Separate lists
        $createdTasks = $tasks;
        $completedTasks = $tasks->where('status', 'completed');
        $inProgressTasks = $tasks->where('status', 'progress');
        $notCompletedTasks = $tasks->where('status', '!=', 'completed');

        $users = User::whereBetween('created_at', [$start, $end])->get();
        $usersCreated = $users->count();

        return view('backend.report.index', compact(
            'tasksCreated', 'tasksCompleted', 'tasksInProgress', 'tasksNotCompleted',
            'usersCreated', 'start', 'end', 'period',
            'createdTasks', 'completedTasks', 'inProgressTasks', 'notCompletedTasks'
        ));
    }
}

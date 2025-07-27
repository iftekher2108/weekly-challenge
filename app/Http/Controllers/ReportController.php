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
        $tasksNotCompleted = $tasks->where('status', 'not_completed')->count();

        // Separate lists
        $createdTasks = $tasks;
        $completedTasks = $tasks->where('status', 'completed');
        $inProgressTasks = $tasks->where('status', 'progress');
        $notCompletedTasks = $tasks->where('status', 'not_completed');

        $users = User::whereBetween('created_at', [$start, $end])->get();
        $usersCreated = $users->count();

        return view('backend.report.index', compact(
            'tasksCreated',
             'tasksCompleted',
              'tasksInProgress',
               'tasksNotCompleted',
            // 'usersCreated',
             'start',
              'end',
               'period',
            'createdTasks',
             'completedTasks',
              'inProgressTasks',
               'notCompletedTasks'
        ));
    }

    public function companyReport(Request $request, $companyId = null)
    {
        $user = Auth::user();
        // Super-admin can view any company, company-admin can view their own
        if ($user->isSuperAdmin()) {
            $company = \App\Models\Company::findOrFail($companyId);
        } else if ($user->isCompanyAdmin($companyId)) {
            $company = $user->companies()->where('companies.id', $companyId)->firstOrFail();
        } else {
            abort(403, 'Unauthorized');
        }

        $start = $request->input('start', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $end = $request->input('end', \Carbon\Carbon::now()->endOfMonth()->toDateString());
        $period = $request->input('period', 'month');

        if ($period === 'week') {
            $start = \Carbon\Carbon::now()->startOfWeek()->toDateString();
            $end = \Carbon\Carbon::now()->endOfWeek()->toDateString();
        } elseif ($period === 'range' && $request->filled(['start', 'end'])) {
            // Use provided start and end
        } else {
            $start = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $end = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $userIds = $company->users()->pluck('users.id');
        $tasks = \App\Models\task::whereBetween('due_date', [$start, $end])
            ->whereIn('user_id', $userIds)
            ->get();
        $tasksCreated = $tasks->count();
        $tasksCompleted = $tasks->where('status', 'completed')->count();
        $tasksInProgress = $tasks->where('status', 'progress')->count();
        $tasksNotCompleted = $tasks->where('status', 'not_completed')->count();

        $createdTasks = $tasks;
        $completedTasks = $tasks->where('status', 'completed');
        $inProgressTasks = $tasks->where('status', 'progress');
        $notCompletedTasks = $tasks->where('status', 'not_completed');

        return view('backend.report.company', compact(
            'company',
            'tasksCreated',
            'tasksCompleted',
            'tasksInProgress',
            'tasksNotCompleted',
            'start',
            'end',
            'period',
            'createdTasks',
            'completedTasks',
            'inProgressTasks',
            'notCompletedTasks'
        ));
    }
}

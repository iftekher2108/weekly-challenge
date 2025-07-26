<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalCompleted = task::where('user_id',Auth::user()->id)->where('status','completed')->count();
        $totalProgress = task::where('user_id',Auth::user()->id)->where('status','progress')->count();
       $totalIncompleted = task::where('user_id',Auth::user()->id)->where('status','not_completed')->count();
        return view('backend.dashboard',compact(
            'totalCompleted',
            'totalProgress',
            'totalIncompleted',

        ));
    }

    public function profile() {
        return view('auth.profile');
    }

}

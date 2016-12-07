<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; //Pour pouvoir utiliser les méthodes de Auth
use Illuminate\Support\Facades\DB;
use App\User;


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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        /*$ids_pro = DB::table('collaboraters')->where('user_id', $user->id)->pluck('project_id');*/  
        return view('home', compact('user'));
    }
}

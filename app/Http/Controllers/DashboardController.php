<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class DashboardController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
		$count = Link::all()->count();

        return view('dashboard', compact('count'));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;

class DashboardController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
		$shopify_count = Sales::where(['shop_source' => 'shopify'])->count();
		$depop_count = Sales::where(['shop_source' => 'depop'])->count();
		$asos_count = Sales::where(['shop_source' => 'asos'])->count();
		$others_count = $depop_count+$asos_count;

        return view('dashboard', compact('shopify_count', 'others_count'));
    }

}

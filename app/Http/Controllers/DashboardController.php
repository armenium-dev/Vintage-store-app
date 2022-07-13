<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class DashboardController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
		$shopify_count = Link::where(['shop_type' => 'shopify'])->count();
		$depop_count = Link::where(['shop_type' => 'depop'])->count();
		$asos_count = Link::where(['shop_type' => 'asos'])->count();
		$others_count = $depop_count+$asos_count;

        return view('dashboard', compact('shopify_count', 'others_count'));
    }

}

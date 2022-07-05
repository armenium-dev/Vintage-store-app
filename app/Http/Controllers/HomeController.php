<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Shopify\Shopify;

class HomeController extends Controller{
    #public $shopify;

    public function __construct(){
        #$this->middleware('auth');
        #$this->shopify = new Shopify;
    }

    public function index(){
        return view('home');
    }


}

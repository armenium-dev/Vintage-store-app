<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	private $shops = [1, 2, 3];

	public function getShopsIDs(): array{
		return $this->shops;
	}

	public function shopifyCount(){
		$shopify_count = Sales::where(['shop_source' => 'shopify'])->count();
		$depop_count = Sales::where(['shop_source' => 'depop'])->count();
		$asos_count = Sales::where(['shop_source' => 'asos'])->count();

		return view('dashboard', compact('shopify_count', 'depop_count', 'asos_count'));
	}

}

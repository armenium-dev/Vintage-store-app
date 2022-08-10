<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\Sales;

class DashboardController extends Controller{

	private $shopifyApi;

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
		$shopify_count = Sales::where(['shop_source' => 'shopify'])->count();
		$depop_count = Sales::where(['shop_source' => 'depop'])->count();
		$asos_count = Sales::where(['shop_source' => 'asos'])->count();

		$statistic = $this->_getStatistic();
		#dd($statistic);

        return view('dashboard', compact('shopify_count', 'depop_count', 'asos_count', 'statistic'));
    }

	private function _getStatistic(): array{
		$result = [];

		foreach($this->getShopsIDs() as $shop_id){
			if(!isset($result['remote']['products_total']))
				$result['remote']['products_total'] = 0;

			if(!isset($result['remote']['variants_total']))
				$result['remote']['variants_total'] = 0;

			if(!isset($result['local']['products_total']))
				$result['local']['products_total'] = 0;

			if(!isset($result['local']['variants_total']))
				$result['local']['variants_total'] = 0;

			$result['titles'][$shop_id] = env("SHOPIFY_SHOP_".$shop_id."_NAME");

			$this->shopifyApi = new MyShopify($shop_id);

			$res = $this->shopifyApi->get('/products/count.json');
			$result['remote'][$shop_id]['products_count'] = $res['count'];

			$res = $this->shopifyApi->get('/variants/count.json');
			$result['remote'][$shop_id]['variants_count'] = $res['count'];

			$result['local'][$shop_id]['products_count'] = Product::whereShopId($shop_id)->count();
			$result['local'][$shop_id]['variants_count'] = Variant::whereShopId($shop_id)->count();

			$result['remote']['products_total'] += $result['remote'][$shop_id]['products_count'];
			$result['remote']['variants_total'] += $result['remote'][$shop_id]['variants_count'];
			$result['local']['products_total'] += $result['local'][$shop_id]['products_count'];
			$result['local']['variants_total'] += $result['local'][$shop_id]['variants_count'];
		}

		return $result;
	}


}

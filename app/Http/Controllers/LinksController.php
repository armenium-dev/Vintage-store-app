<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Product;
use Illuminate\Http\Request;

class LinksController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function linksShopify(){
		$title = __('Sales on Shopify');

		$shop_1_links = Link::where(['shop_id' => 1, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();
		$shop_2_links = Link::where(['shop_id' => 2, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();
		$shop_3_links = Link::where(['shop_id' => 3, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();

		return view('links.index', compact('shop_1_links', 'shop_2_links', 'shop_3_links'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function linksDepopAsos(){
		$shop_1_links = Link::where(['shop_id' => 1, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();
		$shop_2_links = Link::where(['shop_id' => 2, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();
		$shop_3_links = Link::where(['shop_id' => 3, 'shop_type' => 'shopify'])->orderByDesc('updated_at')->get();

		return view('links.index', compact('shop_1_links', 'shop_2_links', 'shop_3_links'));
	}


	public function remove(Request $request){
		$model = Link::find($request->post('id'));
		$model->delete();

		$error = $model->trashed() ? 0 : 1;

		return response()->json(compact('error'));
	}

}

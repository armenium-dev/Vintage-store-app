<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Sales $sales
	 * @return \Illuminate\Http\Response
	 */
	public function show(Sales $sales){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Sales $sales
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Sales $sales){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Sales $sales
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Sales $sales){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Sales $sales
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Sales $sales){
		//
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function salesOnShopify(){
		$title = __('Sales on Shopify');

		$shop_1_sales = Sales::where(['shop_id' => 1, 'shop_source' => 'shopify'])->orderBy('order_id')->get();
		$shop_2_sales = Sales::where(['shop_id' => 2, 'shop_source' => 'shopify'])->orderBy('order_id')->get();
		$shop_3_sales = Sales::where(['shop_id' => 3, 'shop_source' => 'shopify'])->orderBy('order_id')->get();

		return view('sales.index', compact('title','shop_1_sales', 'shop_2_sales', 'shop_3_sales'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function salesOnDepop(){
		$title = __('Sales on Depop');

		$shop_1_sales = Sales::where(['shop_id' => 1, 'shop_source' => 'depop'])->orderBy('order_id')->get();
		$shop_2_sales = Sales::where(['shop_id' => 2, 'shop_source' => 'depop'])->orderBy('order_id')->get();
		$shop_3_sales = Sales::where(['shop_id' => 3, 'shop_source' => 'depop'])->orderBy('order_id')->get();

		return view('sales.index', compact('title','shop_1_sales', 'shop_2_sales', 'shop_3_sales'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function salesOnAsos(){
		$title = __('Sales on Asos');

		$shop_1_sales = Sales::where(['shop_id' => 1, 'shop_source' => 'asos'])->orderBy('order_id')->get();
		$shop_2_sales = Sales::where(['shop_id' => 2, 'shop_source' => 'asos'])->orderBy('order_id')->get();
		$shop_3_sales = Sales::where(['shop_id' => 3, 'shop_source' => 'asos'])->orderBy('order_id')->get();

		return view('sales.index', compact('title','shop_1_sales', 'shop_2_sales', 'shop_3_sales'));
	}


	public function remove(Request $request){
		$model = Sales::find($request->post('id'));
		if($model)
			$model->delete();

		$error = $model->trashed() ? 0 : 1;

		return response()->json(compact('error'));
	}

}

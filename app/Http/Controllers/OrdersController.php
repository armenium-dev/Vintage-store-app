<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller {

	public ShopifyController $ShopifyController;

	public function __construct(ShopifyController $SC){
		$this->ShopifyController = $SC;
	}

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
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		//
	}

	/**
	 * ACTION for manual testing
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\View
	 */
	public function storeCustomOrderByID(Request $request){
		$result = 0;

		if($request->isMethod('post')){
			$shop_id = $request->get('shop_id');
			$order_id = $request->get('order_id');

			$result = $this->ShopifyController->storeOrder($shop_id, $order_id);
		}

		return view('orders.store-by-id', compact('result'));
	}

}

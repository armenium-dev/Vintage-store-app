<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

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
		$orders = Order::whereIsMysteryBox(1)->get();

		return view('orders.index', compact('orders'));
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
	 * @param Request $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function importOrderByID(Request $request){
		$order_id = $request->get('order_id');

		$shops = [
			1 => env('SHOPIFY_SHOP_1_NAME'),
			2 => env('SHOPIFY_SHOP_2_NAME'),
			3 => env('SHOPIFY_SHOP_3_NAME'),
		];

		return view('orders.import-by-id', compact('shops'));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function storeOrderByID(Request $request){
		$message = '';
		$order_id = 0;

		if($request->isMethod('post')){
			$shop_id = $request->post('shop_id');
			$order_id = $request->post('order_id');

			$order = Order::where(['shop_id' => $shop_id, 'order_id' => $order_id])->first();

			$result = $this->ShopifyController->storeOrder($shop_id, $order_id);

			if(!is_null($order)){
				if($result){
					$message = sprintf(__('Order %s status updated successfully!'), $order_id);
				}else{
					$message = sprintf(__('This order %s already exists. No need to import it.'), $order_id);
				}
			}else{
				if($result){
					$message = sprintf(__('Order %s imported successfully!'), $order_id);
				}else{
					$message = sprintf(__('Order %s not fount on this shop! Try with another shop.'), $order_id);
				}
			}

		}

		return redirect(route('importOrderByID', ['order_id' => $order_id]))->with('status', $message);
	}

}

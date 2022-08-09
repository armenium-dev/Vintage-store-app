<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class OrdersController extends Controller {

	public ShopifyController $ShopifyController;
	public MysteryBoxController $MysteryBoxController;

	public function __construct(ShopifyController $SC, MysteryBoxController $MBC){
		$this->ShopifyController = $SC;
		$this->MysteryBoxController = $MBC;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index(){
		$orders = Order::whereIsMysteryBox(0)->orderByDesc('updated_at')->get();

		return view('orders.index', compact('orders'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function mysteryBox(){
		$orders = Order::whereIsMysteryBox(1)->orderByDesc('updated_at')->get();

		return view('orders.mystery', compact('orders'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function mysteryBoxCollect($id){
		$order = Order::whereId($id)->first();
		#dd($order->data['line_items']);

		$line_items = [];

		foreach($order->data['line_items'] as $line_item){
			$product = Product::where(['product_id' => $line_item['product_id'], 'is_mystery' => 1])->first();
			#dd($product->title);
			if($product){
				$line_items[] = [
					'product_image' => $product->image,
					'product_id' => $line_item['product_id'],
					'product_title' => $line_item['title'],
					'variant_id' => $line_item['variant_id'],
					'variant_title' => $line_item['variant_title']
				];
			}
		}

		#dd($line_items);

		return view('orders.collect', compact('order', 'line_items'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function mysteryBoxCollectProducts($oid, $pid, $vid){
		$order = Order::whereId($oid)->first();
		$product = Product::where(['product_id' => $pid, 'is_mystery' => 1])->first();
		$variant = Variant::where(['product_id' => $pid, 'variant_id' => $vid])->first();
		#dd([$order->data['line_items'], $product, $variant]);

		$this->MysteryBoxController->getMysteryBoxItems($order, $product, $variant);

		return view('orders.collect-products', compact('order', 'product', 'variant'));
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

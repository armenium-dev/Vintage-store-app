<?php
namespace App\Http\Controllers;

use App\Models\MysteryBox;
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
		$orders = Order::whereIsMysteryBox(1)
			->whereNull('fulfillment_status')
			->orWhere('fulfillment_status', '!=', 'fulfilled')
			->orderByDesc('updated_at')
			->get();

		return view('orders.mystery', compact('orders'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function mysteryBoxCollect($id){
		$order = Order::whereId($id)->first();


		$line_items = [];

		foreach($order->data['line_items'] as $line_item){
			$product = Product::where(['product_id' => $line_item['product_id'], 'is_mystery' => 1])->first();

			if($product){
				$collected_data = $this->MysteryBoxController->getBoxLineCollectedData($order->order_id, $line_item);
				$line_items[] = [
					'line_id' => $line_item['id'],
					'product_image' => $product->image,
					'product_id' => $line_item['product_id'],
					'product_title' => $line_item['title'],
					'variant_id' => $line_item['variant_id'],
					'variant_title' => $line_item['variant_title'],
					'collected_count' => $collected_data['count'],
					'collected_total' => $collected_data['total'],
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
	public function mysteryBoxCollectProducts($oid, $lid, $pid, $vid){
		$order = Order::whereOrderId($oid)->first();
		$product = Product::where(['product_id' => $pid, 'is_mystery' => 1])->first();
		$variant = Variant::where(['product_id' => $pid, 'variant_id' => $vid])->first();

		#dd([$order->data['line_items'], $product, $variant]);

		$box_items = $this->MysteryBoxController->getMysteryBoxItems($order, $product, $variant, $lid);
		#dd($box_items);

		return view('orders.collect-products', compact('order', 'product', 'variant', 'box_items', 'lid'));
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

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function storeOrderMysteryBox(Request $request){
		$messages = [];
		$order_id = 0;

		if($request->isMethod('post')){
			$order_id = $request->post('order_id');
			$items = $request->post('items');
			$delete_data = $insert_data = [];
			#dd($order_id);

			foreach($items as $item){
				$a = explode(':', $item);
				$delete_data[] = [
					'formula' => $a[0],
					'order_id' => $a[1],
					'line_id' => $a[2],
					'packed' => 0
				];
				$insert_data[] = [
					'formula' => $a[0],
					'order_id' => $a[1],
					'line_id' => $a[2],
					'product_id' => $a[3],
					'variant_id' => $a[4],
					#'packed' => 0
				];
			}

			if(!empty($delete_data)){
				foreach($delete_data as $data){
					MysteryBox::where($data)->delete();
				}
			}

			if(!empty($insert_data)){
				foreach($insert_data as $data){
					if($mystery_box = MysteryBox::create($data)){
						$messages[] = sprintf(__('Mystery Box %s created successfully!'), $mystery_box->id);
					}else{
						$messages[] = __('Error! Mystery Box not created');
					}
				}
			}
		}

		return redirect(route('mysteryBoxCollect', ['id' => $order_id]))->with('status', implode("\n", $messages));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(){}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id){}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){}

}

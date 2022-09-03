<?php
namespace App\Http\Controllers;

use App\Http\Helpers\Parser;
use App\Models\MysteryBox;
use App\Models\MysteryBoxProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller {

	public ShopifyController $ShopifyController;
	public MysteryBoxController $MysteryBoxController;
	public Parser $Parser;

	public function __construct(ShopifyController $SC, MysteryBoxController $MBC, Parser $P){
		$this->ShopifyController = $SC;
		$this->MysteryBoxController = $MBC;
		$this->Parser = $P;
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
	public function mysteryBox(Request $request){
		$finished = (int) $request->get('finished');
		$fulfilled = $request->get('fulfilled');
		$ordering = $request->get('ordering');

		$query = MysteryBox::query();
		$query->select([
			'orders.id as id',
			'orders.shop_id as shop_id',
			'orders.order_id as order_id',
			'orders.payment_status as payment_status',
			'orders.fulfillment_status as fulfillment_status',
			'orders.data as data',
			'orders.updated_at as updated_at',
			DB::raw('data->> "$.name" as num'),
			DB::raw('data->> "$.total_price" as total_price'),
			'mystery_boxes.line_id as line_id',
			'mystery_boxes.finished as finished',
			'mystery_boxes.pdf_file as pdf_file',
		]);
		$query->whereNull('orders.deleted_at');
		$query->where(['orders.is_mystery_box' => 1]);

		$query->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id');

		$query->where(['mystery_boxes.finished' => $finished]);

		if(intval($fulfilled) == 1){
			$query->where(function($query){
				return $query->whereNotNull('orders.fulfillment_status')->orWhere(['orders.fulfillment_status' => 'fulfilled']);
			});
		}else{
			$query->where(function($query){
				return $query->whereNull('orders.fulfillment_status')->orWhere('orders.fulfillment_status', '!=', 'fulfilled');
			});
		}

		switch($ordering){
			case "date-asc":
				$query->orderBy('orders.updated_at');
				break;
			case "date-desc":
				$query->orderByDesc('orders.updated_at');
				break;
			case "id-desc":
				$query->orderByDesc('orders.order_id');
				break;
			case "id-asc":
			default:
				$query->orderBy('orders.order_id');
				break;
		}

		#$query->dd();

		$orders = $query->get();

		return view('orders.mystery', [
			'orders' => $orders,
			'total' => $orders->count(),
			'filter' => [
				'finished' => $finished,
				'fulfilled' => $fulfilled,
				'ordering' => $ordering,
			]
		]);
	}

	/*
	public function mysteryBox(Request $request){
		$finished = $request->get('finished');
		$fulfilled = $request->get('fulfilled');
		$ordering = $request->get('ordering');

		$query = Order::query();
		$query->where(['orders.is_mystery_box' => 1]);

		if($finished){
			$query->leftJoin('mystery_boxes', 'mystery_boxes.order_id', '=', 'orders.order_id');
			$query->where(['mystery_boxes.finished' => intval($finished)]);
		}

		if(intval($fulfilled) == 1){
			$query->where(function($query){
				return $query->whereNotNull('orders.fulfillment_status')->orWhere(['orders.fulfillment_status' => 'fulfilled']);
			});
		}else{
			$query->where(function($query){
				return $query->whereNull('orders.fulfillment_status')->orWhere('orders.fulfillment_status', '!=', 'fulfilled');
			});
		}

		switch($ordering){
			case "date-asc":
				$query->orderBy('orders.updated_at');
				break;
			case "date-desc":
				$query->orderByDesc('orders.updated_at');
				break;
			case "id-desc":
				$query->orderByDesc('orders.order_id');
				break;
			case "id-asc":
			default:
				$query->orderBy('orders.order_id');
				break;
		}

		$orders = $query->get();

		return view('orders.mystery', [
			'orders' => $orders,
			'total' => $orders->count(),
			'filter' => [
				'finished' => $finished,
				'fulfilled' => $fulfilled,
				'ordering' => $ordering,
			]
		]);
	}
	*/

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

		$box_items = $this->MysteryBoxController->getMysteryBoxProducts($order, $product, $variant, $lid);
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
			#dd($items);

			foreach($items as $item){
				$a = explode(':', $item);
				$delete_data[] = [
					'formula' => $a[0],
					'order_id' => $a[1],
					'line_id' => $a[2],
					'packed' => 0
				];
				$product_sorting_tag_data = $this->getProductSortingTag($a[3]);
				$insert_data[] = [
					'formula' => $a[0],
					'order_id' => $a[1],
					'line_id' => $a[2],
					'product_id' => $a[3],
					'variant_id' => $a[4],
					'tag' => $product_sorting_tag_data['tag'],
					'sort_num_1' => $product_sorting_tag_data['num_1'],
					'sort_num_2' => $product_sorting_tag_data['num_2'],
					#'packed' => 0
				];
			}

			if(!empty($delete_data)){
				foreach($delete_data as $data){
					MysteryBoxProduct::where($data)->delete();
				}
			}

			if(!empty($insert_data)){
				foreach($insert_data as $data){
					if($mystery_box = MysteryBoxProduct::create($data)){
						$messages[] = sprintf(__('Mystery Box %s created successfully!'), $mystery_box->id);
					}else{
						$messages[] = __('Error! Mystery Box not created');
					}
				}
			}
		}

		return redirect(route('mysteryBoxCollect', ['id' => $order_id]))->with('status', implode("\n", $messages));
	}

	private function getProductSortingTag($product_id): array{
		$res = ['tag' => '', 'num_1' => 0, 'num_2' => 0];

		$product = Product::whereProductId($product_id)->first();
		#dd($product);

		if(is_null($product)) return $res;

		$tag = $this->Parser->getVCUKtag($product->body);

		if(empty($tag)) return $res;
		#dump($tag);

		$res['tag'] = $tag;
		$str = $tag;

		if(str_contains($str, 'VCUK')){
			$str = str_replace('VCUK', '', $str);
		}elseif(str_contains($str, 'TV')){
			$str = str_replace('TV', '', $str);
		}

		$str = trim($str, ':');
		$str = trim($str, '-');

		if(str_contains($str, ':')){
			$a = explode(':', $str);
			$res['num_1'] = $a[0];
			$res['num_2'] = $a[1];
		}elseif(str_contains($str, '-')){
			$a = explode('-', $str);
			$res['num_1'] = $a[0];
			$res['num_2'] = $a[1];
		}else{
			$res['num_1'] = $str;
		}

		return $res;
	}

	public function createOrdersMysteryBoxes(){
		$query = Order::query();
		#$query->leftJoin('mystery_boxes', 'mystery_boxes.order_id', '!=', 'orders.order_id');
		$query->where(['orders.is_mystery_box' => 1]);
		$orders = $query->get();

		$result = [];

		foreach($orders as $order){
			$order_id = $order['order_id'];
			foreach($order->data['line_items'] as $item){
				if(str_contains(strtolower($item['title']), 'mystery')){
					$line_id = $item['id'];
					$mb = MysteryBox::firstOrCreate(
						['order_id' => $order_id, 'line_id' => $line_id],
						['order_id' => $order_id, 'line_id' => $line_id, 'finished' => 0]
					);

					if($mb) $result[] = ['order_id' => $order_id, 'line_id' => $line_id];
				}

			}
		}

		dd($result);
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
	 * @param \App\Models\Order $order
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function destroy(Order $order, Request $request){
		$order_id = $order->order_id;

		$order->delete();
		$error = $order->trashed() ? 0 : 1;

		if($error == 0){
			MysteryBox::where('order_id', $order_id)->delete();
			MysteryBoxProduct::where('order_id', $order_id)->delete();
		}

		if($request->type == 'ajax'){
			return response()->json(compact('error', $request->id));
		}else{
			return redirect()->route('mysteryBox')->with('status', 'Order Deleted');
		}

	}

}

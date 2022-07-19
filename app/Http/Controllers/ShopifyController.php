<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Uploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Sales;
use Illuminate\Support\Facades\Log;

class ShopifyController extends Controller {

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $shopify;
	private $update_interval = 86400;

	public function __construct(){
		#$this->shopify = new MyShopify(1);
		#$this->updateShopifyProductsTable();
	}

	/**
	 * ACTION for manual testing
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function setOrderProducts(Request $request){
		$shop_id = $request->get('shop_id');
		$order_id = $request->get('order_id');

		return $this->parseAndStoreOrderData($shop_id, $order_id);
	}

	public function parseAndStoreOrderData($shop_id, $order_id){
		$data = $this->_getOrderProducts($shop_id, $order_id);

		$add_order = false;
		$products = $data['products'];
		$order = $data['order'];

		if(!empty($products)){
			foreach($products as $product){
				$product_id = $product['product']['id'];
				$title = $product['product']['title'];
				$body = $product['product']['body_html'];
				$status = $product['product']['status'];
				$p_updated_at = $product['product']['updated_at'];
				$tags = $this->_pareseProductTags($product['product']['tags']);
				$variants = $product['product']['variants'];

				if(empty($tags['link_depop']) && empty($tags['link_asos'])) continue;

				Product::updateOrCreate(
					['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => 0],
					[
						'shop_id' => $shop_id,
						'product_id' => $product_id,
						'variant_id' => 0,
						'title' => $title,
						'body' => $body,
						'qty' => 0,
						'status' => $status,
						'p_updated_at' => $p_updated_at,
						'link_depop' => $tags['link_depop'],
						'link_asos' => $tags['link_asos'],
					]
				);

				if(count($variants)){
					foreach($variants as $variant){
						Product::updateOrCreate(
							['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => $variant['id']],
							[
								'shop_id' => $shop_id,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['id'],
								'title' => $variant['title'],
								'qty' => $variant['inventory_quantity'],
							]
						);

						$tags['link_depop'] = trim($tags['link_depop']);
						$tags['link_asos'] = trim($tags['link_asos']);

						if(intval($variant['inventory_quantity']) == 0){
							if(!empty($tags['link_depop'])){
								Sales::updateOrCreate(
									[
										'shop_id' => $shop_id,
										'order_id' => $order_id,
										'product_id' => $variant['product_id'],
										'variant_id' => $variant['id'],
										'link' => $tags['link_depop'],
										'shop_source' => 'shopify',
										'link_type' => 'depop',
									],
									[
										'shop_id' => $shop_id,
										'order_id' => $order_id,
										'product_id' => $variant['product_id'],
										'variant_id' => $variant['id'],
										'link' => $tags['link_depop'],
										'shop_source' => 'shopify',
										'link_type' => 'depop',
									]
								);
							}
							if(!empty($tags['link_asos'])){
								Sales::updateOrCreate(
									[
										'shop_id' => $shop_id,
										'order_id' => $order_id,
										'product_id' => $variant['product_id'],
										'variant_id' => $variant['id'],
										'link' => $tags['link_asos'],
										'shop_source' => 'shopify',
										'link_type' => 'asos',
									],
									[
										'shop_id' => $shop_id,
										'order_id' => $order_id,
										'product_id' => $variant['product_id'],
										'variant_id' => $variant['id'],
										'link' => $tags['link_asos'],
										'shop_source' => 'shopify',
										'link_type' => 'asos',
									]
								);
							}
						}
					}
				}

				$add_order = true;
			}

			if($add_order){
				$this->_addOrder($shop_id, $order);
			}
		}

		return true;
	}

	private function _getOrderProducts($shop_id, $order_id){
		$products = [];

		$shopify_client = new MyShopify($shop_id);

		$result = $shopify_client->get('/orders/'.$order_id.'.json');
		#dd($result);

		if(!isset($result['ERROR'])){
			/*Order::updateOrCreate(
				['shop_id' => $shop_id, 'order_id' => $result['order']['id']],
				[
					'shop_id' => $shop_id,
					'order_id' => $result['order']['id'],
					'payment_status' => $result['order']['financial_status'],
					'fulfillment_status' => $result['order']['fulfillment_status'],
					'data' => json_encode($result['order']),
				]
			);*/

			if(isset($result['order']['line_items'])){
				foreach($result['order']['line_items'] as $product){
					$products[] = $shopify_client->get('/products/'.$product['product_id'].'.json');
				}
			}
		}

		#dd(json_encode($products));
		return ['order' => $result, 'products' => $products];
	}

	private function _addOrder($shop_id, $order){
		Order::updateOrCreate(
			['shop_id' => $shop_id, 'order_id' => $order['order']['id']],
			[
				'shop_id' => $shop_id,
				'order_id' => $order['order']['id'],
				'payment_status' => $order['order']['financial_status'],
				'fulfillment_status' => $order['order']['fulfillment_status'],
				'data' => json_encode($order['order']),
			]
		);
	}

	private function _pareseProductTags($tags){
		$t = ['link_asos' => '', 'link_depop' => ''];

		if(empty($tags)) return $t;

		$a = array_map('trim', explode(',', $tags));

		foreach($a as $v){
			if($v != 'NOTASOS' && strstr($v, 'asos') !== false){
				$t['link_asos'] = $v;
			}
			if($v != 'NOTDEPOP' && strstr($v, 'depop') !== false){
				$t['link_depop'] = $v;
			}
		}

		return $t;
	}

	/**-------------CRON METHODS----------------**/

	public function turnOffShopifyProducts(): int|array{
		$res = [];

		$uploads = Uploads::where(['parsed' => 1, 'processed' => 0])->get()->toArray();
		#Log::stack(['cron'])->debug($uploads);

		if(empty($uploads)) return 0;

		$group_csv = $group_html = [];

		foreach($uploads as $upload){
			if(empty($upload['content'])) continue;

			switch($upload['file_type']){
				case "csv":
					$group_csv = array_merge($group_csv, json_decode($upload['content'], true));
					break;
				case "html":
					$group_html = array_merge($group_html, json_decode($upload['content'], true));
					break;
				default:
					break;
			}

			/** Uncomment on production */
			#Uploads::find($upload['id'])->update(['processed' => 1]);
		}

		if(!empty($group_csv)){
			$group_csv = array_unique($group_csv);
			$res[] = $this->_findProductsAndTurnOff('depop', $group_csv);
		}

		if(!empty($group_html)){
			$group_html = array_unique($group_html);
			$res[] = $this->_findProductsAndTurnOff('asos', $group_html);
		}

		#Log::stack(['cron'])->debug($group_csv);
		#Log::stack(['cron'])->debug($group_html);

		return $res;
	}

	private function _findProductsAndTurnOff($type, $data): int{
		Log::stack(['cron'])->debug(__METHOD__);

		$products = [];
		foreach($data as $item){
			if($type == 'depop'){
				$product = Product::where('body', 'like', '%'.$item.'%')->get()->toArray();
			}elseif($type = 'asos'){
				$product = Product::where(['link_asos' => $item])->get()->toArray();
			}

			if(!empty($product)){
				$products[] = $product;
			}
		}

		if(!empty($products)){
			#Log::stack(['cron'])->debug($products);

			/*foreach($products as $product){
				$variant = Product::where(['product_id' => $product['product_id']])->where('variant_id', '!=', 0)->get()->toArray();
				if(intval($variant['qty']) > 0){
					if(!empty($product['link_depop'])){
						Sales::updateOrCreate(
							[
								'shop_id' => $variant['shop_id'],
								'order_id' => 0,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['variant_id'],
								'link' => $product['link_depop'],
								'shop_source' => 'depop',
								'link_type' => 'depop',
							],
							[
								'shop_id' => $variant['shop_id'],
								'order_id' => 0,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['variant_id'],
								'link' => $product['link_depop'],
								'shop_source' => 'depop',
								'link_type' => 'depop',
							]
						);
					}
					if(!empty($product['link_asos'])){
						Sales::updateOrCreate(
							[
								'shop_id' => $variant['shop_id'],
								'order_id' => 0,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['variant_id'],
								'link' => $product['link_asos'],
								'shop_source' => 'asos',
								'link_type' => 'asos',
							],
							[
								'shop_id' => $variant['shop_id'],
								'order_id' => 0,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['variant_id'],
								'link' => $product['link_asos'],
								'shop_source' => 'asos',
								'link_type' => 'asos',
							]
						);
					}
				}
			}*/
		}

		/*foreach($this->getShopsIDs() as $shop_id){
			#$shopify_client = new MyShopify($shop_id);
			#$result = $shopify_client->get('/orders/'.$order_id.'.json');
		}*/

		return count($products);
	}



}

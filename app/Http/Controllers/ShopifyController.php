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

	private MyShopify $shopify_client;

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

	/**-------------- WEBHOOK ORDERS ----------------**/

	public function storeOrder($shop_id, $order_id): bool{
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
				$tags = $this->_parseProductTags($product['product']['tags']);
				$variants = $product['product']['variants'];

				#if(empty($tags['link_depop']) && empty($tags['link_asos'])) continue;

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

	private function _getOrderProducts($shop_id, $order_id): array{
		$products = [];

		$this->shopify_client = new MyShopify($shop_id);

		$order = $this->shopify_client->get('/orders/'.$order_id.'.json');
		#Log::stack(['webhook'])->debug($order);

		if(!isset($order['ERROR'])){
			if(isset($order['order']['line_items'])){
				foreach($order['order']['line_items'] as $product){
					$products[] = $this->_getProduct($product['product_id']);
				}
			}
		}

		return ['order' => $order, 'products' => $products];
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

	private function _parseProductTags($tags): array{
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

	private function _getProduct($product_id){
		$product = $this->shopify_client->get('/products/'.$product_id.'.json?fields=id,title,body_html,status,updated_at,tags,variants');
		#Log::stack(['webhook'])->debug($product);

		return $product;
	}

	/**-------------- WEBHOOK PRODUCTS ----------------**/

	public function createOrUpdateProduct($shop_id, $product_id): bool{
		$this->shopify_client = new MyShopify($shop_id);
		$product = $this->_getProduct($product_id);
		#Log::stack(['webhook'])->debug($product);

		$title = $product['product']['title'];
		$body = $product['product']['body_html'];
		$status = $product['product']['status'];
		$p_updated_at = $product['product']['updated_at'];
		$tags = $this->_parseProductTags($product['product']['tags']);
		$variants = $product['product']['variants'];

		#if(empty($tags['link_depop']) && empty($tags['link_asos'])) return false;

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
			}
		}

		return true;
	}

	public function deleteProduct($shop_id, $product_id): bool{
		return Product::where(['shop_id' => $shop_id, 'product_id' => $product_id])->delete();
	}

	/**------------- CRON METHODS ----------------**/

	public function getDepopAsosSalesProducts(): int|array{
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
			Uploads::find($upload['id'])->update(['processed' => 1]);
		}

		if(!empty($group_csv)){
			$group_csv = array_unique($group_csv);
			$res['depop'] = $this->_findDepopAsosProductsAndAddToSale('depop', $group_csv);
		}

		if(!empty($group_html)){
			$group_html = array_unique($group_html);
			$res['asos'] = $this->_findDepopAsosProductsAndAddToSale('asos', $group_html);
		}

		#Log::stack(['cron'])->debug($group_csv);
		#Log::stack(['cron'])->debug($group_html);

		return $res;
	}

	private function _findDepopAsosProductsAndAddToSale($type, $data): int{
		Log::stack(['cron'])->debug(__METHOD__);
		Log::stack(['cron'])->debug($type);
		#Log::stack(['cron'])->debug($data);

		$select_fields = ['id', 'shop_id', 'product_id', 'variant_id', 'title', 'link_depop', 'link_asos'];
		$products = [];
		foreach($data as $item){
			if($type == 'depop'){
				$product = Product::where('body', 'like', '%'.$item.'%')
					->select($select_fields)->get()->toArray();
			}elseif($type = 'asos'){
				$product = Product::where(['link_asos' => $item])
					->select($select_fields)->get()->toArray();
			}

			if(!empty($product)){
				$products[] = [
					'id' => $product[0]['id'],
					'shop_id' => $product[0]['shop_id'],
					'product_id' => $product[0]['product_id'],
					'variant_id' => $product[0]['variant_id'],
					'title' => $product[0]['title'],
					'link_depop' => $product[0]['link_depop'],
					'link_asos' => $product[0]['link_asos'],
				];
			}
		}

		if(!empty($products)){
			#Log::stack(['cron'])->debug($products);

			foreach($products as $product){
				$variant = Product::where(['product_id' => $product['product_id']])->where('variant_id', '!=', 0)->get()->toArray();
				$variant = $variant[0];
				#Log::stack(['cron'])->debug($variant['qty']);
				if(intval($variant['qty']) > 0){

					if(!empty($product['link_'.$type])){
						$link = $product['link_'.$type];
						$shop_source = $type;
						$link_type = $type;
					}

					Sales::firstOrCreate(
						[
							'shop_id' => $variant['shop_id'],
							'order_id' => 0,
							'product_id' => $variant['product_id'],
							'variant_id' => $variant['variant_id'],
							'link' => $link,
							'shop_source' => $shop_source,
							'link_type' => $link_type,
							'removed' => 0,
						],
						[
							'shop_id' => $variant['shop_id'],
							'order_id' => 0,
							'product_id' => $variant['product_id'],
							'variant_id' => $variant['variant_id'],
							'link' => $link,
							'shop_source' => $shop_source,
							'link_type' => $link_type,
							'removed' => 0,
						]
					);
				}
			}
		}


		return count($products);
	}

	public function turnOffShopifyProducts(): int|array{
		$res = [];

		$sale = Sales::where(['removed' => 0])->orderBy('shop_id')->first();

		if(!empty($sale)){
			$sale = $sale->toArray();
			Log::stack(['cron'])->debug($sale);

			$shopify_client = new MyShopify($sale['shop_id']);
			$variant = $shopify_client->get('/variants/'.$sale['variant_id'].'.json');
			#Log::stack(['cron'])->debug($variant);

			if(!empty($variant)){
				$inventory_item_id = $variant['variant']['inventory_item_id'];
				$inventory_quantity = intval($variant['variant']['inventory_quantity']);

				if($inventory_quantity > 0){
					$inventory_levels = $shopify_client->get('/inventory_levels.json?inventory_item_ids='.$inventory_item_id);
					#Log::stack(['cron'])->debug($inventory_levels);

					if(!empty($inventory_levels)){
						foreach($inventory_levels as $levels){
							foreach($levels as $level){
								$data = [
									"location_id" => $level['location_id'],
									"inventory_item_id" => $level['inventory_item_id'],
									"available" => intval($level['available'])-1
								];
								$result = $shopify_client->post('/inventory_levels/set.json', $data);
								#Log::stack(['cron'])->debug($result);
								if(!empty($result['inventory_level'])){
									$res[] = $result['inventory_level']['available'];
									$this->_removeSale($sale, $data["available"]);
								}
								unset($result);
							}
						}
					}
				}else{
					$this->_removeSale($sale);
				}
			}
			unset($shopify_client);
		}

		return count($res);
	}

	private function _removeSale($sale_data, $available = 0){
		Sales::where(['id' => $sale_data['id']])->update(['removed' => 1]);
		Sales::where(['id' => $sale_data['id']])->delete();

		$select_fields = ['id', 'link_depop', 'link_asos'];
		$product = Product::where(['shop_id' => $sale_data['shop_id'], 'product_id' => $sale_data['product_id']])->select($select_fields)->get()->toArray();

		if(!empty($product)){
			Product::where(['shop_id' => $sale_data['shop_id'], 'product_id' => $sale_data['product_id']])
				->where('variant_id', '!=', 0)
				->update(['qty' => $available]);

			if($sale_data['shop_source'] == 'depop'){
				$link = $product[0]['link_asos'];
				$shop_source = 'depop';
				$link_type = 'asos';
			}elseif($sale_data['shop_source'] == 'asos'){
				$link = $product[0]['link_depop'];
				$shop_source = 'asos';
				$link_type = 'depop';
			}

			Sales::firstOrCreate(
				[
					'shop_id' => $sale_data['shop_id'],
					'order_id' => $sale_data['order_id'],
					'product_id' => $sale_data['product_id'],
					'variant_id' => $sale_data['variant_id'],
					'link' => $link,
					'shop_source' => $shop_source,
					'link_type' => $link_type,
					'removed' => 1,
				],
				[
					'shop_id' => $sale_data['shop_id'],
					'order_id' => 0,
					'product_id' => $sale_data['product_id'],
					'variant_id' => $sale_data['variant_id'],
					'link' => $link,
					'shop_source' => $shop_source,
					'link_type' => $link_type,
					'removed' => 1,
				]
			);

		}
	}

}

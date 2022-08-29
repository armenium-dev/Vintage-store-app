<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Tag;
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
	private TagsController $TagsController;
	private ProductsController $ProductsController;

	public function __construct(ProductsController $PC, TagsController $TC){
		$this->ProductsController = $PC;
		$this->TagsController = $TC;
	}


	/**-------------- WEBHOOK ORDERS ----------------**/

	public function storeOrder($shop_id, $order_id): bool{
		$data = $this->_getOrderProducts($shop_id, $order_id);

		$add_order = false;
		$products = $data['products'];
		$order = $data['order'];
		$is_mystery_box_order = 0;

		if(!empty($products)){
			foreach($products as $product){
				$product_id = $product['product']['id'];
				$title = $product['product']['title'];
				$body = $product['product']['body_html'];
				$image = $product['product']['image']['src'] ?? '';
				$status = $product['product']['status'];
				$p_updated_at = $product['product']['updated_at'];
				$variants = $product['product']['variants'];
				$tags = $this->TagsController->parseProductTags($product['product']['tags']);

				if(str_contains(strtolower($title), 'mystery')){
					$is_mystery_box_order = 1;
				}

				$this->ProductsController->updateOrCreate([
					'shop_id' => $shop_id,
					'product_id' => $product_id,
					'title' => $title,
					'body' => $body,
					'status' => $status,
					'p_updated_at' => $p_updated_at,
					'link_depop' => $tags['link_depop'],
					'link_asos' => $tags['link_asos'],
					'image' => $image,
					'tags' => $tags['tags'],
				]);

				#$this->TagsController->renewTags($shop_id, $product_id, $tags['tags']);

				if(count($variants)){
					foreach($variants as $variant){
						Variant::updateOrCreate(
							['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => $variant['id']],
							[
								'shop_id' => $shop_id,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['id'],
								'title' => $variant['title'],
								'option1' => $variant['option1'],
								'option2' => $variant['option2'],
								'option3' => $variant['option3'],
								'inventory_quantity' => $variant['inventory_quantity'],
								'price' => $variant['price'],
							]
						);

						$tags['link_depop'] = trim($tags['link_depop']);
						$tags['link_asos'] = trim($tags['link_asos']);

						// Эта проверка нужна для того, чтобы определиться,
						// нужно ли удалять этот товар на площадках Depop и Asos,
						// если этого товара не осталось на Shopify магазине.
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
				$this->_addOrder($shop_id, $order, $is_mystery_box_order);
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
					#Log::stack(['webhook'])->debug($product);
					if(!is_null($product['product_id'])){
						$products[] = $this->_getProduct($product['product_id']);
					}
				}
			}
		}

		return ['order' => $order, 'products' => $products];
	}

	private function _addOrder($shop_id, $order, $is_mystery_box){
		Order::updateOrCreate(
			['shop_id' => $shop_id, 'order_id' => $order['order']['id']],
			[
				'shop_id' => $shop_id,
				'order_id' => $order['order']['id'],
				'is_mystery_box' => $is_mystery_box,
				'payment_status' => $order['order']['financial_status'],
				'fulfillment_status' => $order['order']['fulfillment_status'],
				'data' => json_encode($order['order']),
			]
		);
	}

	private function _getProduct($product_id){
		$product = $this->shopify_client->get('/products/'.$product_id.'.json?fields=id,title,body_html,status,updated_at,tags,variants,image');
		#Log::stack(['webhook'])->debug($product_id);
		#Log::stack(['webhook'])->debug($product);

		return $product;
	}

	private function _getProductOnlineStoreUrl($shop_id, $product_id){
		$url = null;

		$params = 'product_'.$product_id.': product(id: "gid://shopify/Product/'.$product_id.'") {id,title,status,productType,publishedAt,onlineStoreUrl,onlineStorePreviewUrl}';
		$query = '{'.$params.'}';
		$response = $this->shopify_client->post('/graphql.json', ["query" => $query]);

		if(!empty($response) && isset($response['data']) && !empty($response['data'])){
			foreach($response['data'] as $key => $product){
				if($shop_id == 3){
					if(!is_null($product['onlineStoreUrl'])){
						$url = $product['onlineStoreUrl'];
					}elseif(!is_null($product['onlineStorePreviewUrl'])){
						$url = $product['onlineStorePreviewUrl'];
					}
				}else{
					if(!is_null($product['onlineStoreUrl'])){
						$url = $product['onlineStoreUrl'];
					}
				}
			}
		}

		/*Log::stack(['webhook'])->debug([
			'shop_id' => $shop_id,
			'product_id' => $product_id,
			'url' => $url,
		]);*/

		return $url;
	}

	/**-------------- WEBHOOK PRODUCTS ----------------**/

	public function createOrUpdateProduct($shop_id, $product_id): bool{
		$this->shopify_client = new MyShopify($shop_id);
		$product = $this->_getProduct($product_id);
		$product_online_store_url = $this->_getProductOnlineStoreUrl($shop_id, $product_id);
		#Log::stack(['webhook'])->debug($product);

		$title = $product['product']['title'];
		$body = $product['product']['body_html'];
		$status = $product['product']['status'];
		$image = $product['product']['image']['src'] ?? '';
		$p_updated_at = $product['product']['updated_at'];
		$variants = $product['product']['variants'];
		#$tags = $this->_parseProductTags($product['product']['tags']);
		$tags = $this->TagsController->parseProductTags($product['product']['tags']);

		#if(empty($tags['link_depop']) && empty($tags['link_asos'])) return false;

		$this->ProductsController->updateOrCreate([
			'shop_id' => $shop_id,
			'product_id' => $product_id,
			'title' => $title,
			'body' => $body,
			'status' => $status,
			'image' => $image,
			'p_updated_at' => $p_updated_at,
			'link_depop' => $tags['link_depop'],
			'link_asos' => $tags['link_asos'],
			'tags' => $tags['tags'],
			'online_store_url' => $product_online_store_url,
		]);

		if(count($variants)){
			foreach($variants as $variant){
				Variant::updateOrCreate(
					['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => $variant['id']],
					[
						'shop_id' => $shop_id,
						'product_id' => $variant['product_id'],
						'variant_id' => $variant['id'],
						'title' => $variant['title'],
						'option1' => $variant['option1'],
						'option2' => $variant['option2'],
						'option3' => $variant['option3'],
						'inventory_quantity' => $variant['inventory_quantity'],
						'price' => $variant['price'],
					]
				);
			}
		}

		return true;
	}

	public function deleteProduct($shop_id, $product_id): bool{
		Variant::where(['shop_id' => $shop_id, 'product_id' => $product_id])->delete();
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

			Log::stack(['cron'])->debug(['upload_id' => $upload['id']]);

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

		$select_fields = ['id', 'shop_id', 'product_id', 'title', 'link_depop', 'link_asos'];
		$products = [];
		foreach($data as $item){
			if($type == 'depop'){
				$product = Product::where('body', 'like', '%'.$item.'%')
					->select($select_fields)->get()->toArray();
			}elseif($type = 'asos'){
				$product = Product::where('link_asos', 'like', '%'.$item.'%')
					->select($select_fields)->get()->toArray();
			}

			if(!empty($product)){
				$products[] = [
					'id' => $product[0]['id'],
					'shop_id' => $product[0]['shop_id'],
					'product_id' => $product[0]['product_id'],
					#'variant_id' => $product[0]['variant_id'],
					'title' => $product[0]['title'],
					'link_depop' => $product[0]['link_depop'],
					'link_asos' => $product[0]['link_asos'],
				];
			}
		}

		if(!empty($products)){
			#Log::stack(['cron'])->debug($products);

			foreach($products as $product){
				$variant = Variant::where(['product_id' => $product['product_id']])->get()->toArray();
				$variant = $variant[0];

				Log::stack(['cron'])->debug([
					'product_id' => $variant['product_id'],
					'variant_id' => $variant['variant_id'],
					'inventory_quantity' => $variant['inventory_quantity'],
				]);

				if(intval($variant['inventory_quantity']) > 0){

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

		if(env('APP_ENV') == 'local') return $res;

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
								$result = $shopify_client->post('/inventory_levels/set.json', $data); // decrease inventory availability via shopify api
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
		$product = Product::where(['shop_id' => $sale_data['shop_id'], 'product_id' => $sale_data['product_id']])
			->select($select_fields)->get()->toArray();

		if(!empty($product)){
			Variant::where(['shop_id' => $sale_data['shop_id'], 'product_id' => $sale_data['product_id'], 'variant_id' => $sale_data['variant_id']])
				->update(['inventory_quantity' => $available]);

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

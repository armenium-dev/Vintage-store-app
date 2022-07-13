<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Product;
use App\Models\Link;
use App\Models\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class ShopifyController extends Controller{

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $shopify;
	private $update_interval = 86400;

	public function __construct(){
		$this->shopify = new MyShopify(1);
		#$this->updateShopifyProductsTable();
	}

	public function getShopifyProductsCount(){
		$count_url = '/admin/products/count';
		$result = $this->shopify->get($count_url);

		return $result->count;
	}

	/**
	 * @return array
	 */
	public function getShopifyProducts(): array{
		$limit = 250;
		$shopify_products = [];

		$products_url = "/admin/products.json?since_id={since_id}&limit={$limit}&fields=id,title";
		$pages_count = intval(ceil($this->getShopifyProductsCount() / $limit));

		$since_id = 0;
		for($i = 1; $i <= $pages_count; $i++){
			$url = str_replace('{since_id}', $since_id, $products_url);
			$result = $this->shopify->get($url);
			foreach($result->products as $product){
				$shopify_products[$product->id] = $product->title;
				$since_id = $product->id;
			}
		}

		#dd($shopify_products);

		return $shopify_products;
	}

	public function updateShopifyProductsTable(){
		$last_update = Settings::get('products_shopify_last_update');
		$update_interval = Settings::get('products_shopify_update_interval');

		$shopify_products_count = $this->getShopifyProductsCount();
		$products_shopify = Product::all(['shopify_id', 'title']);

		if($last_update == 0 || time() > ($last_update + $update_interval) || $shopify_products_count != $products_shopify->count()){
			$shopify_products = $this->getShopifyProducts();

			$tmp_products_shopify = $products_shopify->toArray();
			$products_shopify = [];
			foreach($tmp_products_shopify as $product){
				$products_shopify[$product['shopify_id']] = $product['title'];
			}

			if($shopify_products){
				foreach($shopify_products as $product_id => $product_name){
					if(in_array($product_id, array_keys($products_shopify))){
						if($products_shopify[$product_id] != $product_name){
							Product::where(['shopify_id' => $product_id])->update(['title' => $product_name]);
						}
						unset($products_shopify[$product_id]);
					}else{
						Product::create(['shopify_id' => $product_id, 'title' => $product_name]);
					}
				}

				if(!empty($products_shopify)){
					$shopify_ids = array_keys($products_shopify);
					Product::whereIn('shopify_id', $shopify_ids)->delete();
				}

			}

			if(empty($update_interval)){
				$update_interval = $this->update_interval;
			}
			$last_update = time();

			Settings::set('products_shopify_last_update', $last_update);
			Settings::set('products_shopify_update_interval', $update_interval);
		}

	}

	/**
	 * @return array
	 */
	public function getSyncShopifyProducts(): array{
		$sync_products = [];

		$shopify_products = $this->getShopifyProducts();

		if(!empty($shopify_products)){
			$shopify_ids = array_keys($shopify_products);
			$Products = DB::table('products')->whereIn('product_id', $shopify_ids)->get();

			foreach($Products->all() as $product){
				if($product->name != $shopify_products[$product->product_id]){
					$sync_products[] = ['id' => $product->id, 'product_id' => $product->product_id, 'name' => $product->name, 'shopify_name' => $shopify_products[$product->product_id],];
				}
			}
		}

		#dd($sync_products);

		return $sync_products;
	}

	/**
	 * ACTION
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function shopifySync(){
		$sync_products = $this->getSyncShopifyProducts();

		return view('shopify.list', ['SyncProducts' => $sync_products]);
	}

	/**
	 * ACTION
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
		$products = $this->_getOrderProducts($shop_id, $order_id);

		if(!empty($products)){
			foreach($products as $product){
				$product_id = $product['product']['id'];
				$title = $product['product']['title'];
				$status = $product['product']['status'];
				$tags = $this->_pareseProductTags($product['product']['tags']);
				$variants = $product['product']['variants'];

				Product::updateOrCreate(
					['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => 0],
					[
						'shop_id' => $shop_id,
						'product_id' => $product_id,
						'variant_id' => 0,
						'title' => $title,
						'qty' => 0,
						'status' => $status,
						'link_depop' => $tags['link_depop'],
						'link_asos' => $tags['link_asos'],
					]
				);

				/*$p = DB::table('products')->where(['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => 0])->get();
				if($p->count() == 0){
					Product::create([
						'shop_id' => $shop_id,
						'product_id' => $product_id,
						'variant_id' => 0,
						'title' => $title,
						'qty' => 0,
						'status' => $status,
						'link_depop' => $tags['link_depop'],
						'link_asos' => $tags['link_asos'],
					]);
				}
				unset($p);*/

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
						/*$p = DB::table('products')->where(['shop_id' => $shop_id, 'product_id' => $product_id, 'variant_id' => $variant['id']])->get();
						if($p->count() == 0){
							Product::create([
								'shop_id' => $shop_id,
								'product_id' => $variant['product_id'],
								'variant_id' => $variant['id'],
								'title' => $variant['title'],
								'qty' => $variant['inventory_quantity'],
							]);
						}
						unset($p);*/

						if(intval($variant['inventory_quantity']) == 0 && (!empty($tags['link_depop']) || !empty($tags['link_asos']))){
							Link::updateOrCreate(
								['shop_id' => $shop_id, 'order_id' => $order_id, 'product_id' => $variant['product_id'], 'variant_id' => $variant['id']],
								[
									'shop_id' => $shop_id,
									'order_id' => $order_id,
									'product_id' => $variant['product_id'],
									'variant_id' => $variant['id'],
									'link_depop' => $tags['link_depop'],
									'link_asos' => $tags['link_asos'],
									'shop_type' => 'shopify',
								]
							);
							/*$l = DB::table('links')->where(['shop_id' => $shop_id, 'product_id' => $variant['product_id'], 'variant_id' => $variant['id']])->get();
							if($l->count() == 0){
								#dd($variant);
								Link::create([
									'shop_id' => $shop_id,
									'order_id' => $order_id,
									'product_id' => $variant['product_id'],
									'variant_id' => $variant['id'],
									'link_depop' => $tags['link_depop'],
									'link_asos' => $tags['link_asos'],
								]);
							}
							unset($l);*/
						}
					}
				}
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
			Order::updateOrCreate(
				['shop_id' => $shop_id, 'order_id' => $result['order']['id']],
				[
					'shop_id' => $shop_id,
					'order_id' => $result['order']['id'],
					'payment_status' => $result['order']['financial_status'],
					'fulfillment_status' => $result['order']['fulfillment_status'],
					'data' => json_encode($result['order']),
				]
			);

			if(isset($result['order']['line_items'])){
				foreach($result['order']['line_items'] as $product){
					$products[] = $shopify_client->get('/products/'.$product['product_id'].'.json');
				}
			}
		}

		#dd(json_encode($products));
		return $products;
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
}

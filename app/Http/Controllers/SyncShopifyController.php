<?php
/**
 * NOT USED
 * NEED TO REMOVE
 */

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Shopify\MyShopify;

class SyncShopifyController extends Controller {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	private $update_interval = 86400;
	private $limit_max = 250;
	private $limit_partials = 20;
	private $shopifyApi;
	private $shop_id;
	private $since_id;
	private $option_name;
	private $fields = ['id', 'title', 'body_html', 'tags', 'updated_at', 'status', 'variants'];

	public function syncShopsProducts(): string{
		$res = [];

		$shops = Settings::getLike('_sync_since_id');
		
		foreach($shops as $name => $value){
			$this->option_name = $name;
			$this->shop_id = intval(explode('_', $name)[1]);
			$this->since_id = $value;
			Log::stack(['cron'])->debug(['shop_id' => $this->shop_id, 'since_id' => $this->since_id]);
			
			$this->shopifyApi = new MyShopify($this->shop_id);
			
			$res[] = $this->updateShopifyProductsTable();
			#$res[] = $this->_getShopifyProducts();
			sleep(1);
		}

		return implode(', ', $res);
	}

	public function getShopifyProductsCount(): int{
		$count_url = '/products/count.json';
		$result = $this->shopifyApi->get($count_url);

		return $result['count'];
	}

	private function _getShopifyAllProducts(): array{
		$shopify_products = [];

		$products_url = "/products.json?since_id={since_id}&limit={$this->limit_max}&fields=id";
		$products_count = $this->getShopifyProductsCount();
		$pages_count = intval(ceil($products_count / $this->limit_max));

		Log::stack(['cron'])->debug([$products_count, $pages_count]);

		$since_id = 0;
		for($i = 1; $i <= $pages_count; $i++){
			$url = str_replace('{since_id}', $since_id, $products_url);
			$result = $this->shopifyApi->get($url);
			foreach($result['products'] as $product){
				$shopify_products[] = $product['id'];
				$since_id = $product['id'];
			}
			sleep(1);
		}

		Log::stack(['cron'])->debug($shopify_products);

		return $shopify_products;
	}

	private function _getShopifyProducts(): array{
		$since_id = $this->since_id;
		
		$url = sprintf("/products.json?since_id=%d&limit=%d&fields=%s", $since_id, $this->limit_partials, implode(',', $this->fields));
		$result = $this->shopifyApi->get($url);
		
		$since_id = empty($result['products']) ? 0 : end($result['products'])['id'];

		Settings::set($this->option_name, $since_id);
		
		return $result['products'];
	}

	public function updateShopifyProductsTable(){
		$shopify_products = $this->_getShopifyProducts();
		
		if(empty($shopify_products)) return '';

		/*$products_shopify = Product::all(['shopify_id', 'title'])->toArray();

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

		}*/


	}

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
	 * @return \Illuminate\Contracts\View\View
	 */
	public function shopifySync(){
		$sync_products = $this->getSyncShopifyProducts();

		return view('shopify.list', ['SyncProducts' => $sync_products]);
	}

}

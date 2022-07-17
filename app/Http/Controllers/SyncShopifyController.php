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

	public $shopify;
	private $update_interval = 86400;

	public function syncShopProducts(): string{
		$res = [];

		foreach($this->getShopsIDs() as $shop_id){
			$this->shopify = new MyShopify($shop_id);

			#$res[] = $this->updateShopifyProductsTable();
			$res[] = $this->_getShopifyProducts();
			sleep(1);
		}

		return implode(', ', $res);
	}

	public function getShopifyProductsCount(): int{
		$count_url = '/products/count.json';
		$result = $this->shopify->get($count_url);

		return $result['count'];
	}

	/**
	 * @return array
	 */
	private function _getShopifyProducts(): array{
		$limit = 250;
		$shopify_products = [];

		$products_url = "/products.json?since_id={since_id}&limit={$limit}&fields=id";
		$products_count = $this->getShopifyProductsCount();
		$pages_count = intval(ceil($products_count / $limit));

		Log::stack(['cron'])->debug([$products_count, $pages_count]);

		$since_id = 0;
		for($i = 1; $i <= $pages_count; $i++){
			$url = str_replace('{since_id}', $since_id, $products_url);
			$result = $this->shopify->get($url);
			foreach($result['products'] as $product){
				$shopify_products[] = $product['id'];
				$since_id = $product['id'];
			}
			sleep(1);
		}

		Log::stack(['cron'])->debug($shopify_products);

		return $shopify_products;
	}

	public function updateShopifyProductsTable(){
		$shopify_products_count = $this->getShopifyProductsCount();
		$products_shopify = Product::all(['shopify_id', 'title']);

		$shopify_products = $this->_getShopifyProducts();

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
	 * @return \Illuminate\Contracts\View\View
	 */
	public function shopifySync(){
		$sync_products = $this->getSyncShopifyProducts();

		return view('shopify.list', ['SyncProducts' => $sync_products]);
	}

}

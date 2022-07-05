<?php

namespace App\Http\Shopify;

use App;
use Illuminate\Support\Facades\Log;

class MyShopify {
	private $api_version = '';

	public function connect(){
		$this->api_version = env('SHOPIFY_API_VERSION');

		$shopify = App::make('ShopifyAPI');

		$shopify->setup([
			'API_KEY'      => env('SHOPIFY_API_KEY'),
			'API_SECRET'   => env('SHOPIFY_API_SECRET'),
			'SHOP_DOMAIN'  => env('SHOPIFY_SHOP_DOMAIN'),
			'ACCESS_TOKEN' => env('SHOPIFY_ACCESS_TOKEN'),
		]);

		return $shopify;
	}

	private function create_url($url){

		$url_fragment = 'admin/api/'.$this->api_version;

		if(strstr($url, 'admin') !== false){
			$url = str_replace('admin', $url_fragment, $url);
		}else{
			$url = $url_fragment.$url;
		}

		Log::stack(['laravel'])->debug('API URL: '.$url);

		return $url;
	}

	public function get($url){
		$get = $this->connect()->call([
			'METHOD' => 'GET',
			'URL'    => $this->create_url($url)
		]);

		return $get;
	}

	public function post($url, $data){
		$post = $this->connect()->call([
			'METHOD' => 'POST',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);

		return $post;
	}

	public function update($url, $data){
		$update = $this->connect()->call([
			'METHOD' => 'PUT',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);

		return $update;
	}

	public function updateDefaultAddress($url){
		$update = $this->connect()->call([
			'METHOD' => 'PUT',
			'URL'    => $this->create_url($url),
		]);

		return $update;
	}

	public function delete($url){
		$delete = $this->connect()->call([
			'METHOD' => 'DELETE',
			'URL'    => $this->create_url($url)
		]);

		return $delete;
	}
}




<?php

namespace App\Http\Shopify;

use App;
use Illuminate\Support\Facades\Log;

class MyShopify {

	private $api_version = '';

    private $local_shop_id = 1;

    public function __construct($local_shop_id = 1){
        $this->local_shop_id = $local_shop_id;
		$this->api_version = env('SHOPIFY_API_VERSION');
    }

    public function getApiVersion(){
        return $this->api_version;
    }

    public function connect(){

		$shopify = App::make('ShopifyAPI');

		$shopify->setup([
			'API_KEY'      => env('SHOPIFY_SHOP_'.$this->local_shop_id.'_API_KEY'),
			'API_SECRET'   => env('SHOPIFY_SHOP_'.$this->local_shop_id.'_API_SECRET'),
			'SHOP_DOMAIN'  => env('SHOPIFY_SHOP_'.$this->local_shop_id.'_DOMAIN'),
			'ACCESS_TOKEN' => env('SHOPIFY_SHOP_'.$this->local_shop_id.'_ADMIN_ACCESS_TOKEN'),
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

        $url = str_replace('{API_VERSION}', $this->api_version, $url);

		Log::stack(['laravel'])->debug('API URL: '.$url);

		return $url;
	}

	public function get($url){
		$get = $this->connect()->call([
			'METHOD' => 'GET',
			'URL'    => $this->create_url($url),
            'RETURNARRAY' => true
		]);

		return $get;
	}

	public function post($url, $data){
		$post = $this->connect()->call([
			'METHOD' => 'POST',
			'URL'    => $this->create_url($url),
			'DATA'   => $data,
            'RETURNARRAY' => true
		]);

		return $post;
	}

	public function update($url, $data){
		$update = $this->connect()->call([
			'METHOD' => 'PUT',
			'URL'    => $this->create_url($url),
			'DATA'   => $data,
            'RETURNARRAY' => true
		]);

		return $update;
	}

	public function delete($url){
		$delete = $this->connect()->call([
			'METHOD' => 'DELETE',
			'URL'    => $this->create_url($url),
            'RETURNARRAY' => true
		]);

		return $delete;
	}
}




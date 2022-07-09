<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;

class WebhooksController extends Controller{

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


	public function __construct(){}

	public function createWebhooks(): RedirectResponse {
		$wh_ops = Settings::getLike('webhook');
		#dd($wh_ops);

		if(!empty($wh_ops)){
			foreach($wh_ops as $k => $v){
				if(intval($v) == 0){
					$shop_id = intval(substr(explode('shop_', $k)[1], 0, 1));
					$webhook_topic = str_replace('shop_'.$shop_id.'_webhook/', '', $k);
					$id = $this->_createWebhook($shop_id, $webhook_topic);
					
					if(false !== $id){
						Settings::set($k, $id);
					}
				}
			}
		}

		return redirect()->route('listWebhooks');
	}

	private function _createWebhook($shop_id, $topic): mixed{
		$shopify_client = new MyShopify($shop_id);
		$json_data      = json_encode([
			"webhook" => [
				"address" => "https://vintage-store-app.digidez.com/api/shop$shop_id-webhook",
				"format"  => "json",
				"fields"  => ["id"],
				"topic"   => $topic,
			]
		]);
		#dd($json_data);
		$result = $shopify_client->post('/webhooks.json', $json_data);

		#dd($result);

		return isset($result['webhook']) ? $result['webhook']['id'] : false;
	}

	public function listWebhooks(){
		$wh_ops = Settings::getLike('webhook');
		$shops = [];
		
		if(!empty($wh_ops)){
			foreach($wh_ops as $k => $v){
				if(intval($v) != 0){
					$shop_id = intval(substr(explode('shop_', $k)[1], 0, 1));
					if(!in_array($shop_id, $shops)){
						$data = $this->_getWebhooks($shop_id);
						dump($data['webhooks']);
						$shops[] = $shop_id;
					}
				}
			}
		}

	}

	private function _getWebhooks($shop_id = 1){
		$shopify_client = new MyShopify($shop_id);
		$result         = $shopify_client->get('/webhooks.json');

		return $result;
	}


}

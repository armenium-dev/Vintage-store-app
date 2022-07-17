<?php

namespace App\Http\Controllers;

use App\Http\Shopify\MyShopify;
use App\Models\Webhooks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;

class WebhooksController extends Controller{

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function list(){
		$webhooks = Webhooks::getHooks(true);
		dump($webhooks);

		$shops = [];

		if(!empty($webhooks)){
			foreach($webhooks as $k => $v){
				$shop_id = intval($v['shop_id']);
				if(!in_array($shop_id, $shops)){
					$data = $this->_getApi($shop_id);
					dump($data['webhooks']);
					$shops[] = $shop_id;
				}
			}
		}

	}

	private function _getApi($shop_id = 1){
		$shopify_client = new MyShopify($shop_id);
		$result         = $shopify_client->get('/webhooks.json');

		return $result;
	}

	public function create(): RedirectResponse {
		$webhooks = Webhooks::getHooks(false);
		#dd($wh_ops);

		if(!empty($webhooks)){
			foreach($webhooks as $webhook){
				if(!is_null($webhook['address'])){
					$webhook['shop_id'] = intval($webhook['shop_id']);
					$webhook['address'] = str_replace('{shop_id}', $webhook['shop_id'], $webhook['address']);
					$webhook['fields'] = json_decode($webhook['fields'], true);
					$id = $this->_createApi($webhook);
					if(false !== $id){
						Webhooks::find($webhook['id'])->update(['webhook_id' => $id, 'active' => 1]);
					}
				}
			}
		}

		return redirect()->route('listWebhooks');
	}

	private function _createApi($webhook): mixed{
		$shopify_client = new MyShopify($webhook['shop_id']);

		$json_data = json_encode([
			"webhook" => [
				"address" => $webhook['address'],
				"format"  => $webhook['format'],
				"fields"  => $webhook['fields'],
				"topic"   => $webhook['topic'],
			]
		]);

		$result = $shopify_client->post('/webhooks.json', $json_data);

		return isset($result['webhook']) ? $result['webhook']['id'] : false;
	}

	public function update(): RedirectResponse {
		$webhooks = Webhooks::getHooks(true);

		if(!empty($webhooks)){
			foreach($webhooks as $webhook){
				$webhook['shop_id'] = intval($webhook['shop_id']);
				$webhook['address'] = str_replace('{shop_id}', $webhook['shop_id'], $webhook['address']);
				$webhook['fields'] = json_decode($webhook['fields'], true);
				$this->_updateApi($webhook);
			}
		}

		return redirect()->route('listWebhooks');
	}

	private function _updateApi($webhook): mixed{
		$shopify_client = new MyShopify($webhook['shop_id']);

		$json_data = json_encode([
			"webhook" => [
				"id" => $webhook['webhook_id'],
				"address" => $webhook['address'],
				"format"  => $webhook['format'],
				"fields"  => $webhook['fields'],
				"topic"   => $webhook['topic'],
			]
		]);

		$result = $shopify_client->update('/webhooks/'.$webhook['webhook_id'].'.json', $json_data);

		return isset($result['webhook']) ? $result['webhook']['id'] : true;
	}


}

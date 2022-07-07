<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Shopify\MyShopify;

class ShopifyController{
	
	public function shop1Webhook(Request $request){
		Log::stack(['webhook'])->debug('Shop 1');
		Log::stack(['webhook'])->debug($request->post());
		
		return response()->json(['status' => 200]);
		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data        = file_get_contents('php://input');
		
		$verified = $this->_verify_webhook($data, $hmac_header, 1);
		
		if($verified){
			# Process webhook payload
			# ...
		}else{
			return response()->json(['status' => 401]);
			#http_response_code(401);
		}
		
	}
	
	public function shop2Webhook(Request $request){
		Log::stack(['webhook'])->debug('Shop 2');
		Log::stack(['webhook'])->debug($request->post());
		
		return response()->json(['status' => 200]);
	}
	
	public function shop3Webhook(Request $request){
		Log::stack(['webhook'])->debug('Shop 3');
		Log::stack(['webhook'])->debug($request->post());
		
		return response()->json(['status' => 200]);
	}
	
	private function _verify_webhook($data, $hmac_header, $shop_id){
		$calculated_hmac = base64_encode(hash_hmac('sha256', $data, env("SHOPIFY_SHOP_".$shop_id."_API_SECRET"), true));
		
		return hash_equals($hmac_header, $calculated_hmac);
	}
	
	public function getOrder($shop_id, $order_id){
		$shopify_client = new MyShopify($shop_id);
		$result = $shopify_client->get('/orders/'.$order_id.'.json');
		
		dd($result);
	}
}

<?php

namespace App\Http\Controllers\API\Shopify;

use App\Http\Controllers\ShopifyController;
use App\Http\Shopify\MyShopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WebhooksController {

    public $ShopifyController;

    public function __construct(ShopifyController $ShopifyController){
        $this->ShopifyController = $ShopifyController;
    }

    public function shop1Webhook(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 1');
		Log::stack(['webhook'])->debug($request->post());
		#Log::stack(['webhook'])->debug($_SERVER);

		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data        = file_get_contents('php://input');
		Log::stack(['webhook'])->debug($data);

		$verified = $this->_verify_webhook($data, $hmac_header, 1);

		if($verified){
			Log::stack(['webhook'])->debug('verified');
			Log::stack(['webhook'])->debug($verified);
            $this->ShopifyController->parseAndStoreOrderData(1, $request->post('id'));
			# Process webhook payload
			# ...
		    return response()->json(['status' => 200]);
		}else{
			Log::stack(['webhook'])->debug('not verified');
			return response()->json(['status' => 401]);
			#http_response_code(401);
		}

	}

	public function shop2Webhook(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 2');
		Log::stack(['webhook'])->debug($request->post());

		return response()->json(['status' => 200]);
	}

	public function shop3Webhook(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 3');
		Log::stack(['webhook'])->debug($request->post());

		return response()->json(['status' => 200]);
	}

	private function _verify_webhook($data, $hmac_header, $shop_id): bool{
		$calculated_hmac = base64_encode(hash_hmac('sha256', $data, env("SHOPIFY_SHOP_".$shop_id."_API_SECRET"), true));

		return hash_equals($hmac_header, $calculated_hmac);
	}

}

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

	private function _verifyWebhook($shop_id): bool{
		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data        = file_get_contents('php://input');
		$calculated_hmac = base64_encode(hash_hmac('sha256', $data, env("SHOPIFY_SHOP_".$shop_id."_API_SECRET"), true));

		return hash_equals($hmac_header, $calculated_hmac);
	}

    public function shop1WebhookOrders(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 1 Order ID: '.$request->post('id'));

		if($this->_verifyWebhook(1)){
			Log::stack(['webhook'])->debug('verified');
            $this->ShopifyController->parseAndStoreOrderData(1, $request->post('id'));
		    return response()->json(['status' => 200]);
		}else{
			Log::stack(['webhook'])->debug('not verified');
			return response()->json(['status' => 401]);
		}

	}

	public function shop2WebhookOrders(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 2 Order ID: '.$request->post('id'));

		if($this->_verifyWebhook(2)){
			Log::stack(['webhook'])->debug('verified');
			$this->ShopifyController->parseAndStoreOrderData(2, $request->post('id'));
			return response()->json(['status' => 200]);
		}else{
			Log::stack(['webhook'])->debug('not verified');
			return response()->json(['status' => 401]);
		}
	}

	public function shop3WebhookOrders(Request $request): JsonResponse{
		Log::stack(['webhook'])->debug('Shop 3 Order ID: '.$request->post('id'));

		if($this->_verifyWebhook(3)){
			Log::stack(['webhook'])->debug('verified');
			$this->ShopifyController->parseAndStoreOrderData(3, $request->post('id'));
			return response()->json(['status' => 200]);
		}else{
			Log::stack(['webhook'])->debug('not verified');
			return response()->json(['status' => 401]);
		}
	}

}

<?php

namespace App\Http\Controllers\API\Shopify;

use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\ProductsController;
use App\Http\Shopify\MyShopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WebhooksController {

    public ShopifyController $ShopifyController;

    public function __construct(ShopifyController $ShopifyController){
        $this->ShopifyController = $ShopifyController;
    }

	private function _log($pattern, $shop_id, $request_id = 0, $str = ''){
		Log::stack(['webhook'])->debug(sprintf($pattern, $shop_id, $request_id, $str));
	}

	private function _verifyWebhook($shop_id, $request_id = 0): bool{
		$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
		$data        = file_get_contents('php://input');
		$calculated_hmac = base64_encode(hash_hmac('sha256', $data, env("SHOPIFY_SHOP_".$shop_id."_API_SECRET"), true));

		$result = hash_equals($hmac_header, $calculated_hmac);

		#$this->_log('Shop %d Request ID: %s Webhook%s verified', $shop_id, $request_id, ($result ? '' : ' not'));

		return $result;
	}

	/** -------------------------- ORDERS --------------------------- **/

    public function shop1WebhookOrders(Request $request): JsonResponse{
		$shop_id = 1;
		$order_id = $request->post('id');

		$this->_log('Shop %d Create Order ID: %s', $shop_id, $order_id);

		if($this->_verifyWebhook($shop_id, $order_id)){
            $this->ShopifyController->storeOrder($shop_id, $order_id);

		    return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop2WebhookOrders(Request $request): JsonResponse{
		$shop_id = 2;
		$order_id = $request->post('id');

		$this->_log('Shop %d Create Order ID: %s', $shop_id, $order_id);

		if($this->_verifyWebhook($shop_id, $order_id)){
			$this->ShopifyController->storeOrder($shop_id, $order_id);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}
	}

	public function shop3WebhookOrders(Request $request): JsonResponse{
		$shop_id = 3;
		$order_id = $request->post('id');

		$this->_log('Shop %d Create Order ID: %s', $shop_id, $order_id);

		if($this->_verifyWebhook($shop_id, $order_id)){
			$this->ShopifyController->storeOrder($shop_id, $order_id);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}
	}

	/** ------------------------ PRODUCTS -------------------------- **/

	public function shop1WebhookProductCreate(Request $request): JsonResponse{
		$shop_id = 1;
		$product_id = $request->post('id');

		$this->_log('Shop %d Create Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop2WebhookProductCreate(Request $request): JsonResponse{
		$shop_id = 2;
		$product_id = $request->post('id');

		$this->_log('Shop %d Create Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop3WebhookProductCreate(Request $request): JsonResponse{
		$shop_id = 3;
		$product_id = $request->post('id');

		$this->_log('Shop %d Create Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop1WebhookProductUpdate(Request $request): JsonResponse{
		$shop_id = 1;
		$product_id = $request->post('id');

		$this->_log('Shop %d Update Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop2WebhookProductUpdate(Request $request): JsonResponse{
		$shop_id = 2;
		$product_id = $request->post('id');

		$this->_log('Shop %d Update Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop3WebhookProductUpdate(Request $request): JsonResponse{
		$shop_id = 3;
		$product_id = $request->post('id');

		$this->_log('Shop %d Update Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->createOrUpdateProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop1WebhookProductDelete(Request $request): JsonResponse{
		$shop_id = 1;
		$product_id = $request->post('id');

		$this->_log('Shop %d Delete Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->deleteProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop2WebhookProductDelete(Request $request): JsonResponse{
		$shop_id = 2;
		$product_id = $request->post('id');

		$this->_log('Shop %d Delete Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->deleteProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

	public function shop3WebhookProductDelete(Request $request): JsonResponse{
		$shop_id = 3;
		$product_id = $request->post('id');

		$this->_log('Shop %d Delete Product ID: %s', $shop_id, $product_id);

		if($this->_verifyWebhook($shop_id, $product_id)){
			$result = $this->ShopifyController->deleteProduct($shop_id, $product_id);
			#$this->_log('Result: %s', $result);

			return response()->json(['status' => 200]);
		}else{
			return response()->json(['status' => 401]);
		}

	}

}

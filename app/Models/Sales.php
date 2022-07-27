<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model{
	use HasFactory, SoftDeletes;

	protected $table = 'sales';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'shop_source',
		'shop_id',
		'order_id',
		'product_id',
		'variant_id',
		'link',
		'link_type',
		'removed',
	];

	private $order = null;

	public function getShopName(){
		$shops = [
			1 => env('SHOPIFY_SHOP_1_NAME'),
			2 => env('SHOPIFY_SHOP_2_NAME'),
			3 => env('SHOPIFY_SHOP_3_NAME'),
		];

		return $shops[$this->shop_id];
	}

	public function getProductName(){
		$p = Product::where(['product_id' => $this->product_id])->pluck('title');

		return $p->count() ? $p[0] : $this->product_id;
	}

	public function getVariantName(){
		$p = Variant::where(['product_id' => $this->product_id, 'variant_id' => $this->variant_id])->pluck('title');

		return $p->count() ? $p[0] : $this->variant_id;
	}

	private function _getOrderData(){
		if(is_null($this->order)){
			if($this->order_id > 0){
				$this->order = Order::where(['order_id' => $this->order_id])->first();
				$this->order->data = json_decode($this->order->data, true);
			}
		}
	}

	public function getOrderAttribute($attr){
		$this->_getOrderData();
		$ret = '';

		if($this->order_id > 0){
			switch($attr){
				case "date":
					$ret = $this->order->data['updated_at'];
					break;
				case "confirmed":
					$ret = intval($this->order->data['confirmed']) ? 'Yes' : 'No';
					break;
				case "payment_status":
					$ret = is_null($this->order->payment_status) ? '---' : $this->order->payment_status;
					break;
				case "fulfillment_status":
					$ret = is_null($this->order->fulfillment_status) ? '---' : $this->order->fulfillment_status;
					break;
			}
		}

		return $ret;
	}
}

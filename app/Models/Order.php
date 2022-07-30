<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\Json;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model{
	use HasFactory, SoftDeletes;

	protected $table = 'orders';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'shop_id',
		'order_id',
		'is_mystery_box',
		'payment_status',
		'fulfillment_status',
		'data',
	];

	/*protected $attributes = [
		'data' => Json::class,
	];*/

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'data' => Json::class,
		#'created_at' => 'datetime:Y-m-d H:i',
		#'updated_at' => 'datetime:Y-m-d H:i',
	];

	public function getShopName(){
		$shops = [
			1 => env('SHOPIFY_SHOP_1_NAME'),
			2 => env('SHOPIFY_SHOP_2_NAME'),
			3 => env('SHOPIFY_SHOP_3_NAME'),
		];
		
		return $shops[$this->shop_id];
	}

	/**
	 * Get the user's first name.
	 *
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	protected function productName1(): Attribute {
		/*if(is_null($this->data)){
			$this->data = json_decode($this->data, true);
		}*/

		return Attribute::make(
			get: fn ($value, $attributes) => $attributes['data'],
		);
	}

	public function productName(): string{
		#dd($this->data);
		#return $this->data['line_items'][0]['title'];

		$res = '';

		if(!empty($this->data)){
			$titles = [];
			foreach($this->data['line_items'] as $item){
				$titles[] = $item['title'];
				$titles[] = $item['variant_title'];
			}
			$res = implode('<br/>', $titles);
		}

		return $res;
	}
}

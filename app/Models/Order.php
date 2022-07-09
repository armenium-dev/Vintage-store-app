<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
		'payment_status',
		'fulfillment_status',
		'data',
	];

}

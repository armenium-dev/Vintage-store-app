<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MysteryBoxProduct extends Model{
	use HasFactory;

	protected $table = 'mystery_box_products';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'order_id',
		'line_id',
		'product_id',
		'variant_id',
		'formula',
		'tag',
		'sort_num_1',
		'sort_num_2',
		'selected',
		'packed',
	];


}

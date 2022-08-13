<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MysteryBox extends Model{
	use HasFactory;

	protected $table = 'mystery_boxes';

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
		'packed',
	];


}

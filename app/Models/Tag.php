<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model{
	use HasFactory;

	public $timestamps = false;
	protected $primary_key = null;
	public $incrementing = false;

	protected $table = 'tags';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'shop_id',
		'product_id',
		'tag',
	];

}
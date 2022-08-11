<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VintageHandpickItems extends Model{
	use HasFactory;

	protected $table = 'vintage_handpick_items';

	public function tags(): HasMany{
		return $this->hasMany(Tag::class, 'product_id', 'product_id');
	}

}

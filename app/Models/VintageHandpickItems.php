<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VintageHandpickItems extends Model{
	use HasFactory;

	protected $table = 'vintage_handpick_items';

}

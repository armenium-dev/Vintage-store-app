<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'variants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'product_id',
        'variant_id',
        'title',
        'option1',
        'option2',
        'option3',
        'inventory_quantity',
        'price',
    ];


}

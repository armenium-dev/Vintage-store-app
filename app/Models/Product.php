<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'products';

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
        'body',
        'qty',
        'status',
        'link_depop',
        'link_asos',
        'p_updated_at',
    ];


}

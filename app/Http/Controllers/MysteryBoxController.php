<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;

class MysteryBoxController extends Controller {

	public function getVintageHandpickItem(){
		$query = Variant::query();
		$query->select('variants.*');
		$query->leftJoin('products', 'variants.product_id', '=', 'products.product_id');
		$query->leftJoin('tags', 'tags.product_id', '=', 'products.product_id');
		$query->where([
			'products.is_mystery' => 0,
			'products.status' => 'active',
			'products.title' => "NOT LIKE %REWORK%",
			'tags.tag' => 'GG',
			'variants.inventory_quantity' => 1,
		]);
		#$query->where('products.title', 'NOT LIKE ');
		
		$data = $query->get();
	}

}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MysteryBox;

class WarehouseController extends Controller {

	public function pick(){
		$query = MysteryBox::query();
		$query->select([
			'products.id',
			'products.product_id',
			'products.image',
			'mystery_boxes.sorting_tag',
			'variants.title',
		]);
		$query->leftJoin('products', 'products.product_id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('variants', 'variants.variant_id', '=', 'mystery_boxes.variant_id');
		$query->where(['packed' => 0]);
		$query->orderBy('mystery_boxes.formula');
		$query->orderBy('mystery_boxes.sorting_tag');
		
		$mystery_boxes = $query->get()->toArray();
		#$query->dd();
		#dd($mystery_boxes);
		
		return view('warehouse.pick', compact('mystery_boxes'));
	}
	
	public function pack(){
	
	}
	
}

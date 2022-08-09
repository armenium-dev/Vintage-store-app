<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VintageHandpickItems;

class MysteryBoxController extends Controller {

	private $order;
	private $product;
	private $variant;
	private $mb_rules = [
		'StandardVintageMysteryBox' => [],
		'VintageMysterySingleItemBox' => [],
		'VintageMysteryDoubleItemBox' => [],
		'PremiumVintageMysteryBox' => [],
		'ReworkSingleMysteryBox' => [],
		'ReworkTripleItemMysteryBox' => [],
	];

	public function getMysteryBoxItems(Order $o, Product $p, Variant $v){
		$this->order = $o;
		$this->product = $p;
		$this->variant = $v;

		$this->getBoxType();
	}

	private function getBoxType(){
		$title = $this->cleanProductTitle($this->product->title);

		#dd($title);

		switch($title){
			case "StandardVintageMysteryBox":
				break;
			case "VintageMysterySingleItemBox":
				break;
			case "VintageMysteryDoubleItemBox":
				break;
			case "PremiumVintageMysteryBox":
				break;
			case "ReworkSingleMysteryBox":
				break;
			case "ReworkTripleItemMysteryBox":
				break;
		}
	}

	public function getVintageHandpickItem(){
		$items = VintageHandpickItems::where();
	}

	private function cleanProductTitle($string): string{
		$string = preg_replace('/\s+/', '', $string);

		if(str_contains($string, '(')){
			$string = explode('(', $string)[0];
		}

		$search = ['&#8234;', '#&lrm;', '&#8236;', chr(226), chr(128), chr(142)];
		$replace = '';

		return str_replace($search, $replace, $string);
	}

}

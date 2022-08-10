<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VintageHandpickItems;
use App\Models\VintageItems;
use App\Models\SweatshirtItems;
use App\Models\ReworkItems;

class MysteryBoxController extends Controller {

	private Order $order;
	private Product $product;
	private Variant $variant;
	private array $mb_rules = [
		'StandardVintageMysteryBox' => [
			'VintageHandpickItems' => 1,
			'VintageItems' => 1,
			'SweatshirtItems' => 1,
		],
		'VintageMysterySingleItemBox' => [
			'VintageHandpickItems' => 1
		],
		'VintageMysteryDoubleItemBox' => [
			'VintageHandpickItems' => 1,
			'SweatshirtItems' => 1,
		],
		'PremiumVintageMysteryBox' => [
			'VintageHandpickItems' => 2,
			'VintageItems' => 2,
			'SweatshirtItems' => 2,
		],
		'ReworkSingleMysteryBox' => [
			'ReworkItems' => 1
		],
		'ReworkTripleItemMysteryBox' => [
			'ReworkItems' => 3
		],
	];

	public function getMysteryBoxItems(Order $o, Product $p, Variant $v){
		$this->order = $o;
		$this->product = $p;
		$this->variant = $v;

		#$this->getBoxType();
		
		$rule = $this->cleanProductTitle($p->title);
		
		return $this->getBoxItems($this->mb_rules[$rule]);
	}

	public function getBoxItems($rule_models){
		/*dd([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		]);*/
		/*$d = VintageHandpickItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();
		dd($d);*/
		
		$items = [];
		foreach($rule_models as $model_name => $count){
			$class = "\\App\\Models\\".$model_name;
			
			$items[$model_name] = $class::where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->get()->toArray();
		}
		
		#dd($items);
		
		return $items;
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

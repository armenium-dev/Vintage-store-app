<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tag;
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
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
			'VintageItems' => ['title' => 'Vintage Items', 'color' => 'blue', 'count' => 1, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'cyan', 'count' => 1, 'items' => []],
		],
		'VintageMysterySingleItemBox' => [
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
		],
		'VintageMysteryDoubleItemBox' => [
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'cyan', 'count' => 1, 'items' => []],
		],
		'PremiumVintageMysteryBox' => [
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'indigo', 'count' => 2, 'items' => []],
			'VintageItems' => ['title' => 'Vintage Items', 'color' => 'blue', 'count' => 2, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'cyan', 'count' => 2, 'items' => []],
		],
		'ReworkSingleMysteryBox' => [
			'ReworkItems' => ['title' => 'Rework Items', 'color' => 'teal', 'count' => 1, 'items' => []],
		],
		'ReworkTripleItemMysteryBox' => [
			'ReworkItems' => ['title' => 'Rework Items', 'color' => 'teal', 'count' => 3, 'items' => []],
		],
	];

	public function getMysteryBoxItems(Order $o, Product $p, Variant $v){
		$this->order = $o;
		$this->product = $p;
		$this->variant = $v;

		#$this->getBoxType();
		
		#$rule = $this->cleanProductTitle($p->title);

		return $this->getBoxItems($this->cleanProductTitle($p->title));
	}

	public function getBoxItems($rule){
		/*dump([
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
		
		$items = $this->mb_rules[$rule];

		foreach($items as $model_name => $data){
			#dump($model_name);
			$items[$model_name]['items'] = $this->{"get$model_name"}();

			/*$class = "\\App\\Models\\".$model_name;
			
			$items[$model_name] = $class::where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->get()->toArray();*/
		}
		
		#dd($items);
		
		return $items;
	}

	private function getVintageHandpickItems(): array{
		$tags = $this->createTagsList();

		$query = VintageHandpickItems::query();
		$query->leftJoin('tags', 'tags.product_id', '=', 'vintage_handpick_items.product_id');
		$query->where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->orWhere([
			'option1' => $this->variant->option2,
		]);
		$query->whereIn('tags.tag', $tags);

		return $query->get()->toArray();
	}

	private function getVintageItems(){
		$items = VintageItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	private function getSweatshirtItems(){
		$items = SweatshirtItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	private function getReworkItems(){
		$items = ReworkItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	private function createTagsList(): array{
		$options = [
			$this->variant->option1,
			$this->variant->option2,
			$this->variant->option3,
		];

		$list = [];

		foreach($options as $option){
			if(!empty($option)){
				$output_array = [];
				preg_match('/\((.*)\)/', $option, $output_array);

				if(!empty($output_array) && !empty($output_array[1])){
					$list[] = str_replace(' ', '-', $output_array[1]);
					$option = preg_replace('/\((.*)\)/', '', $option);
					$option = trim($option);
				}

				if(str_contains($option, ' ')){
					$list += explode(' ', $option);
				}else $list[] = $option;
			}
		}

		return array_map('trim', $list);
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

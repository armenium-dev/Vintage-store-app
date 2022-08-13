<?php

namespace App\Http\Controllers;

use App\Models\MysteryBox;
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
	private int $line_id = 0;
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

	public function getMysteryBoxItems(Order $o, Product $p, Variant $v, int $line_id){
		$this->order = $o;
		$this->product = $p;
		$this->variant = $v;
		$this->line_id = $line_id;

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
		$query->where(['inventory_quantity' => 1]);
		$query->whereBetween('price', [30, 61]);
		$query->where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->orWhere([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
		])->orWhere([
			'option1' => $this->variant->option2,
		]);
		$query->whereIn('tags.tag', $tags);
		$query->dd();
		dd($query->toSql());
		$result = $query->get()->toArray();

		return $this->setSelectedItems($result, 'VintageHandpickItems');
	}

	/** TODO **/
	private function getVintageItems(){
		$items = VintageItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	/** TODO **/
	private function getSweatshirtItems(){
		$items = SweatshirtItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	/** TODO **/
	private function getReworkItems(){
		$items = ReworkItems::where([
			'option1' => $this->variant->option1,
			'option2' => $this->variant->option2,
			'option3' => $this->variant->option3,
		])->get()->toArray();

		return $items;
	}

	private function setSelectedItems($items, $formula){
		if(!empty($items)){
			foreach($items as $k => $item){
				$count = MysteryBox::where([
					'order_id' => $this->order->order_id,
					'line_id' => $this->line_id,
					'product_id' => $item['product_id'],
					'variant_id' => $item['variant_id'],
					'formula' => $formula,
					'packed' => 0
				])->count();
				$items[$k]['exist'] = $count;
			}
		}

		#dd($items);

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
			if(str_contains(strtolower($option), 'ring') || str_contains(strtolower($option), 'necklace')){
				continue;
			}
			
			if(!empty($option)){
				$output_array = [];
				preg_match('/\((.*)\)/', $option, $output_array);
				
				if(!empty($output_array) && !empty($output_array[1])){
					if(str_contains($output_array[1], ' ')){
						foreach(explode(' ', $output_array[1]) as $b)
							$list[] = $b;
						
						$list[] = str_replace(' ', '-', $output_array[1]);
					}
					
					$option = preg_replace('/\((.*)\)/', '', $option);
					$option = trim($option);
				}

				if(str_contains($option, ' ')){
					foreach(explode(' ', $option) as $b)
						$list[] = $b;
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

	public function getBoxLineCollectedData($order_id, $line_item): array{
		$count = 0;
		$total = 0;
		$rule = $this->cleanProductTitle($line_item['title']);

		foreach($this->mb_rules[$rule] as $formula => $v){
			$total += $v['count'];

			$count += MysteryBox::where([
				'order_id' => $order_id,
				'line_id' => $line_item['id'],
				'formula' => $formula,
				'packed' => 0
			])->count();
		}

		return ['count' => $count, 'total' => $total];
	}
}

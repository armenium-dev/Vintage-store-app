<?php

namespace App\Http\Controllers;

use App\Models\CustomProduct;
use App\Models\JewelryItems;
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
use Illuminate\Support\Facades\DB;

class MysteryBoxController extends Controller {

	private Order $order;
	private Product $product;
	private Variant $variant;
	private int $line_id = 0;
	private array $tags;
	private array $exclude_product_ids;
	private array $exclude_custom_product_ids;
	private array $mb_rules = [
		'StandardVintageMysteryBox' => [
			'RepetitiveItems' => ['title' => 'Repetitive Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
			'JewelryItems' => ['title' => 'Jewelry Items', 'color' => 'blue', 'count' => 1, 'items' => []],
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'cyan', 'count' => 1, 'items' => []],
			'VintageItems' => ['title' => 'Vintage Items', 'color' => 'teal', 'count' => 1, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'emerald', 'count' => 1, 'items' => []],
		],
		'VintageMysterySingleItemBox' => [
			'RepetitiveItems' => ['title' => 'Repetitive Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'cyan', 'count' => 1, 'items' => []],
		],
		'VintageMysteryDoubleItemBox' => [
			'RepetitiveItems' => ['title' => 'Repetitive Items', 'color' => 'indigo', 'count' => 1, 'items' => []],
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'cyan', 'count' => 1, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'emerald', 'count' => 1, 'items' => []],
		],
		'PremiumVintageMysteryBox' => [
			'RepetitiveItems' => ['title' => 'Repetitive Items', 'color' => 'indigo', 'count' => 2, 'items' => []],
			'JewelryItems' => ['title' => 'Jewelry Items', 'color' => 'blue', 'count' => 2, 'items' => []],
			'VintageHandpickItems' => ['title' => 'Vintage Handpick Items', 'color' => 'cyan', 'count' => 2, 'items' => []],
			'VintageItems' => ['title' => 'Vintage Items', 'color' => 'teal', 'count' => 2, 'items' => []],
			'SweatshirtItems' => ['title' => 'Sweatshirt Items', 'color' => 'emerald', 'count' => 2, 'items' => []],
		],
		'ReworkSingleMysteryBox' => [
			'ReworkItems' => ['title' => 'Rework Items', 'color' => 'lime', 'count' => 1, 'items' => []],
		],
		'ReworkTripleItemMysteryBox' => [
			'ReworkItems' => ['title' => 'Rework Items', 'color' => 'lime', 'count' => 3, 'items' => []],
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
		$this->setExcludeProductIDs();
		$this->createTagsList();

		$items = $this->mb_rules[$rule];

		foreach($items as $model_name => $data){
			$items[$model_name]['items'] = $this->{"get$model_name"}();
		}

		#dd($items);

		return $items;
	}

	private function getVintageHandpickItems(): array{
		$query = VintageHandpickItems::query();
		$query->leftJoin('tags', 'tags.product_id', '=', 'vintage_handpick_items.product_id');

		if(!empty($this->exclude_product_ids)){
			$query->whereNotIn('vintage_handpick_items.product_id', $this->exclude_product_ids);
		}

		$query->where(function($query){
			return $query->where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->orWhere([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
			])->orWhere([
				'option1' => $this->variant->option2,
			]);
		});

		$query->whereIn('tags.tag', $this->tags);
		#$query->dd();
		$result = $query->get()->toArray();

		#$result = $this->filterAvailableOnOnlineStore($query->get());
		
		return $this->setSelectedItems($result, 'VintageHandpickItems');
	}

	private function getVintageItems(): array{
		$query = VintageItems::query();
		#$query->leftJoin('tags', 'tags.product_id', '=', 'vintage_items.product_id');

		if(!empty($this->exclude_product_ids)){
			$query->whereNotIn('vintage_items.product_id', $this->exclude_product_ids);
		}

		$query->where(function($query){
			return $query->where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->orWhere([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
			])->orWhere([
				'option1' => $this->variant->option2,
			]);
		});

		#$query->whereIn('tags.tag', $this->tags);

		$result = $query->get()->toArray();

		return $this->setSelectedItems($result, 'VintageItems');
	}

	private function getSweatshirtItems(): array{
		$query = SweatshirtItems::query();

		if(!empty($this->exclude_product_ids)){
			$query->whereNotIn('sweatshirt_items.product_id', $this->exclude_product_ids);
		}

		$query->where(function($query){
			return $query->where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->orWhere([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
			])->orWhere([
				'option1' => $this->variant->option2,
			]);
		});

		$result = $query->get()->toArray();

		return $this->setSelectedItems($result, 'SweatshirtItems');
	}

	private function getReworkItems(): array{
		$query = ReworkItems::query();
		#$query->leftJoin('tags', 'tags.product_id', '=', 'rework_items.product_id');

		if(!empty($this->exclude_product_ids)){
			$query->whereNotIn('rework_items.product_id', $this->exclude_product_ids);
		}

		$query->where(function($query){
			return $query->where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->orWhere([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
			])->orWhere([
				'option1' => $this->variant->option2,
			]);
		});

		#$query->whereIn('tags.tag', $this->tags);

		$result = $query->get()->toArray();

		return $this->setSelectedItems($result, 'ReworkItems');
	}

	private function getJewelryItems(): array{
		$query = JewelryItems::query();
		#$query->leftJoin('tags', 'tags.product_id', '=', 'rework_items.product_id');

		if(!empty($this->exclude_product_ids)){
			$query->whereNotIn('jewelry_items.product_id', $this->exclude_product_ids);
		}

		/*$query->where(function($query){
			return $query->where([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
				'option3' => $this->variant->option3,
			])->orWhere([
				'option1' => $this->variant->option1,
				'option2' => $this->variant->option2,
			])->orWhere([
				'option1' => $this->variant->option2,
			]);
		});*/

		#$query->whereIn('tags.tag', $this->tags);

		$result = $query->get()->toArray();

		return $this->setSelectedItems($result, 'JewelryItems');
	}

	private function getRepetitiveItems(): array{
		$query = CustomProduct::query();
		$query->select(['id', 'id as product_id', 'title as product_title', 'price', 'size as variant_title', 'image', DB::raw('0 as variant_id')]);
		#$query->leftJoin('tags', 'tags.product_id', '=', 'rework_items.product_id');

		if(!empty($this->exclude_custom_product_ids)){
			$query->whereNotIn('id', $this->exclude_custom_product_ids);
		}

		$query->where(function($query){
			return $query->where([
				'size' => $this->variant->option1,
			])->orWhere([
				'size' => $this->variant->option2,
			])->orWhere([
				'size' => $this->variant->option3,
			]);
		});

		$query->where('count', '>', 0);

		$result = $query->get()->toArray();

		#dd($result);

		return $this->setSelectedItems($result, 'RepetitiveItems');
	}

	private function setSelectedItems($items, $formula): array{
		$new_items = [];
		
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
				
				if($count){
					$new_items[] = $items[$k];
					unset($items[$k]);
				}
			}
			$new_items += $items;
		}else $new_items = $items;

		#dd($items);

		return $new_items;
	}

	private function createTagsList(){
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

		$this->tags = array_map('trim', $list);
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

	private function setExcludeProductIDs(){
		$this->exclude_product_ids = MysteryBox::where([/*'selected' => 0,*/ 'packed' => 0])
			->where('order_id', '!=', $this->order->order_id)
			->where('formula', '!=', 'RepetitiveItems')
			->get()
			->pluck('product_id')
			->toArray();


		$mb_custom_products_with_count_and_id = MysteryBox::where(['packed' => 0, 'formula' => 'RepetitiveItems'])
			->where('order_id', '!=', $this->order->order_id)
			->select([DB::raw('COUNT(*) as count'), 'product_id'])
			->groupBy('product_id')
			->get()
			->toArray();

		if(!is_null($mb_custom_products_with_count_and_id)){
			$p_ids = [];
			foreach($mb_custom_products_with_count_and_id as $item){
				$p_id = CustomProduct::where(['id' => $item['product_id']])
					->where('count', '<=', $item['count'])
					->get()
					->pluck('id')->pop();

				if(!is_null($p_id)){
					$this->exclude_custom_product_ids[] = $p_id;
				}
			}
		}

		#dd($mb_custom_products_with_count_and_id);
		#dd([$this->exclude_product_ids, $this->exclude_custom_product_ids]);
	}

}

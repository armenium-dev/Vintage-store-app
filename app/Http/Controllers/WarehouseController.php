<?php
namespace App\Http\Controllers;

use App\Models\CustomProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\MysteryBox;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller {

	private $ordering_keys = [
		'VintageHandpickItems' => [],
		'VintageItems' => [],
		'ReworkItems' => [],
		'SweatshirtItems' => [],
	];

	public function pick(){
		$query = MysteryBox::query();
		$query->select([
			'mystery_boxes.id',
			'products.title as product_title',
			'mystery_boxes.tag',
			'mystery_boxes.formula',
			'products.image',
			'mystery_boxes.selected',
			'orders.data as order_data',
			'mystery_boxes.product_id',
			#'mystery_boxes.variant_id',
			#'variants.title as variant_title',
		]);
		#$query->leftJoin('products_custom', 'products_custom.id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('products', 'products.product_id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id');
		#$query->leftJoin('variants', 'variants.variant_id', '=', 'mystery_boxes.variant_id');
		$query->where(['mystery_boxes.packed' => 0]);
		#$query->orderBy('mystery_boxes.formula');
		$query->orderBy('mystery_boxes.selected');
		$query->orderBy('mystery_boxes.sort_num_1');
		$query->orderBy('mystery_boxes.sort_num_2');

		$mystery_boxes = $query->get()->toArray();
		#$query->dd();
		#dump($mystery_boxes);

		#$mystery_boxes = $this->sortResult($mystery_boxes);
		$mystery_boxes = $this->sortResult2($mystery_boxes);
		$mystery_boxes = $this->setRepetitiveItems($mystery_boxes);
		#dd($mystery_boxes);


		return view('warehouse.pick', compact('mystery_boxes'));
	}

	private function sortResult($items){
		$new_items = [];

		foreach($items as $item)
			$this->ordering_keys[$item['formula']][] = $item;
		#dd($this->ordering_keys);
		foreach($this->ordering_keys as $key => $items)
			foreach($items as $item)
				$new_items[] = $item;

		return $new_items;
	}

	private function sortResult2($items){
		$new_items = [];
		$SweatshirtItems = [];

		foreach($items as $item){
			if(!empty($item['order_data'])){
				$data = json_decode($item['order_data'], true);
				$item['order_num'] = $data['name'];
			}else $item['order_num'] = '';

			unset($item['order_data']);

			if($item['formula'] == 'SweatshirtItems'){
				$SweatshirtItems[] = $item;
			}else $new_items[] = $item;
		}

		if(!empty($SweatshirtItems))
			foreach($SweatshirtItems as $item)
				$new_items[] = $item;

		return $new_items;
	}

	private function setRepetitiveItems($mystery_boxes){
		if(empty($mystery_boxes)) return $mystery_boxes;

		$mystery_boxes_r = [];
		foreach($mystery_boxes as $k => $mystery_box){
			if($mystery_box['formula'] == 'RepetitiveItems'){
				$custom_product = CustomProduct::find($mystery_box['product_id']);
				if(!is_null($custom_product)){
					$mystery_boxes_r[$k] = $mystery_box;
					$mystery_boxes_r[$k]['product_title'] = sprintf('%s / %s', $custom_product->title, $custom_product->size);
					$mystery_boxes_r[$k]['image'] = $custom_product->image;
					$mystery_boxes_r[$k]['category'] = $custom_product->category;
					$mystery_boxes_r[$k]['price'] = $custom_product->price;

					unset($mystery_boxes[$k]);
				}
			}
		}

		return array_merge($mystery_boxes_r, $mystery_boxes);
	}

	public function pickProduct(Request $request): JsonResponse{
		$model = MysteryBox::find($request->post('id'));
		$selected = ($model->selected == 1) ? 0 : 1;

		$model->update(['selected' => $selected]);

		$error = 0;

		return response()->json(compact('error', 'selected'));

	}

	public function pack(){
		$query = MysteryBox::query();
		$query->select([
			'mystery_boxes.id',
			'mystery_boxes.order_id',
			'mystery_boxes.product_id',
			'mystery_boxes.line_id',
			'mystery_boxes.tag',
			'mystery_boxes.price as new_price',
			'products.title as product_title',
			'products.image',
			'variants.price',
			'orders.data',
			'mystery_boxes.formula',
		]);
		$query->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id');
		$query->leftJoin('products', 'products.product_id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('variants', 'variants.variant_id', '=', 'mystery_boxes.variant_id');
		$query->where(['mystery_boxes.packed' => 0, 'mystery_boxes.selected' => 1]);
		$query->orderBy('mystery_boxes.order_id');

		$mystery_boxes = $query->get()->toArray();
		#dump($mystery_boxes);
		$mystery_boxes = $this->setRepetitiveItems($mystery_boxes);
		$mystery_boxes = $this->groupResults($mystery_boxes);

		#dd($mystery_boxes);

		return view('warehouse.pack', compact('mystery_boxes'));
	}

	private function groupResults($items): array{
		$grouped_items = [];

		foreach($items as $item){
			$item['data'] = json_decode($item['data'], true);
			#dump($item['data']['line_items']);
			$order_id = $item['order_id'];
			$line_id = $item['line_id'];
			$id = sprintf('%s:%s', $order_id, $line_id);

			$grouped_items[$id]['name'] = $item['data']['name'];

			foreach($item['data']['line_items'] as $product){
				if(str_contains(strtolower($product['title']), 'mystery') && $line_id == $product['id']){
					$grouped_items[$id]['title'] = $product['name'];
				}
			}
			unset($item['data']);

			if($item['new_price'] > $item['price'])
				$item['price'] = $item['new_price'];

			$grouped_items[$id]['products'][] = $item;
		}

		return $grouped_items;
	}

	public function packProduct(Request $request): JsonResponse{
		$id = $request->post('id');
		$prices = $request->post('prices');
		$a = explode(':', $id);
		$order_id = $a[0];
		$line_id = $a[1];

		$this->addSales($order_id, $line_id);
		$this->decrementCustomProduct($order_id, $line_id);

		MysteryBox::where([
			'order_id' => $order_id,
			'line_id' => $line_id,
		])->update(['packed' => 1]);

		if(!empty($prices)){
			foreach($prices as $product_id => $price){
				MysteryBox::where([
					'order_id' => $order_id,
					'line_id' => $line_id,
					'product_id' => $product_id,
				])->update(['price' => $price]);
			}
		}

		$pdf_url = $this->createPDF($order_id, $line_id);

		$file_name = basename($pdf_url);

		Order::whereOrderId($order_id)->update([
			'finished' => 1,
			'pdf_file' => $file_name,
		]);

		/*$order = Order::whereOrderId($order_id);
		$order->delete();*/

		$error = 0;

		return response()->json(compact('error', 'pdf_url', 'file_name'));
	}

	private function addSales($order_id, $line_id){
		$order = Order::whereOrderId($order_id)->first();

		$mystery_boxes = MysteryBox::where(['order_id' => $order_id, 'line_id' => $line_id, 'selected' => 1, 'packed' => 0])
			->where('formula', '!=', 'RepetitiveItems')->get();

		if($mystery_boxes->count()){
			foreach($mystery_boxes as $mystery_box){
				$product = Product::whereProductId($mystery_box->product_id)->first();

				if($product->count()){
					$links = [];

					if(!empty($product->link_depop)){
						$links[] = [
							'link' => $product->link_depop,
							'type' => 'depop',
						];
					}
					if(!empty($product->link_asos)){
						$links[] = [
							'link' => $product->link_asos,
							'type' => 'asos',
						];
					}

					if(!empty($links)){
						foreach($links as $link){
							Sales::firstOrCreate(
								[
									'shop_id' => $order->shop_id,
									'order_id' => $order_id,
									'product_id' => $mystery_box->product_id,
									'variant_id' => $mystery_box->variant_id,
									'shop_source' => 'shopify',
									'link_type' => $link['type'],
									'removed' => 0,
								],
								[
									'shop_id' => $order->shop_id,
									'order_id' => $order_id,
									'product_id' => $mystery_box->product_id,
									'variant_id' => $mystery_box->variant_id,
									'link' => $link['link'],
									'shop_source' => 'shopify',
									'link_type' => $link['type'],
									'removed' => 0,
								]
							);
						}

						/*Variant::where([
							'shop_id' => $order->shop_id,
							'product_id' => $mystery_box->product_id,
							'variant_id' => $mystery_box->variant_id,
						])->decrement('inventory_quantity', 1);*/
					}
				}
			}
		}
	}

	private function decrementCustomProduct($order_id, $line_id){
		$mystery_boxes = MysteryBox::where([
			'order_id' => $order_id,
			'line_id' => $line_id,
			'selected' => 1,
			'packed' => 0,
			'formula' => 'RepetitiveItems'
		])->get();

		if($mystery_boxes->count()){
			foreach($mystery_boxes as $mystery_box){
				CustomProduct::find($mystery_box->product_id)->decrement('count', 1);
			}
		}
	}

	// Generate PDF
	private function createPDF($order_id, $line_id){
		$box_price = $this->getBoxPrice($order_id, $line_id);
		$box_items = $this->getBoxItemsAndTotals($order_id, $line_id);

		
		// share data to view
		#view()->share('employee', $data);

		if(!is_dir(public_path('downloads')))
			mkdir(public_path('downloads'), 0755);

		$suffix = time();
		$save_file = public_path(sprintf('%s%s%d-%s-%d.pdf', 'downloads', DIRECTORY_SEPARATOR, $order_id, 'warehouse', $suffix));
		$save_file_url = url(sprintf('%s%s%d-%s-%d.pdf', 'downloads', '/', $order_id, 'warehouse', $suffix));

		#PDF::setOption(['chroot' => __DIR__]);
		$dompdf = PDF::loadView('warehouse.pdf', [
			'box_items_count' => count($box_items['items']),
			'box_items' => $box_items['items'],
			'box_total' => $box_items['total'],
			'box_price' => $box_price,
			'total_saving' => $box_items['total'] - $box_price,
		]);
		$dompdf->setPaper([0, 0, 380, 600], 'portrait');
		$dompdf->setOption(['dpi' => 300, 'defaultFont' => 'Helvetica', 'font_height_ratio' => 1]);
		$dompdf->save($save_file);

		return $save_file_url;
	}

	private function getBoxPrice($order_id, $line_id): float{
		$box_price = 0;

		$order = Order::where(['order_id' => $order_id, 'is_mystery_box' => 1])->first();

		if(is_null($order)) return $box_price;

		#dd($order->data['line_items']);

		foreach($order->data['line_items'] as $item){
			if($line_id == $item['id']){
				$box_price = $item['price'];
			}
		}

		return $box_price;
	}

	private function getBoxItemsAndTotals($order_id, $line_id): array{
		$res = ['total' => 0, 'items' => []];

		$query = MysteryBox::query();
		$query->select([
			'mystery_boxes.id',
			#'mystery_boxes.order_id',
			#'mystery_boxes.product_id',
			#'mystery_boxes.line_id',
			#'mystery_boxes.tag',
			'products.title as product_title',
			'mystery_boxes.price as new_price',
			'variants.price',
			#'products.image',
			#'orders.data',
			'mystery_boxes.formula',
			'products_custom.price as r_price',
			'products_custom.title as r_title',
		]);
		$query->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id');
		$query->leftJoin('products', 'products.product_id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('variants', 'variants.variant_id', '=', 'mystery_boxes.variant_id');
		$query->leftJoin('products_custom', 'products_custom.id', '=', 'mystery_boxes.product_id');
		$query->where([
			'mystery_boxes.order_id' => $order_id,
			'mystery_boxes.line_id' => $line_id,
			'mystery_boxes.packed' => 1,
			'mystery_boxes.selected' => 1
		]);
		$query->orderBy('mystery_boxes.order_id');

		$mystery_boxes = $query->get()->toArray();

		#dd($mystery_boxes);

		if(!is_null($mystery_boxes)){
			foreach($mystery_boxes as $mystery_box){
				if($mystery_box['formula'] == 'RepetitiveItems'){
					$title = $mystery_box['r_title'];
					$price = !is_null($mystery_box['new_price']) ? $mystery_box['new_price'] : $mystery_box['r_price'];
				}else{
					$title = $mystery_box['product_title'];
					$price = !is_null($mystery_box['new_price']) ? $mystery_box['new_price'] : $mystery_box['price'];
				}
				$res['items'][$mystery_box['id']] = ['title' => $title, 'price' => $price];
				$res['total'] += $price;
			}
		}

		return $res;
	}


}

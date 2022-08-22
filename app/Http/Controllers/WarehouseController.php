<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\MysteryBox;
use Illuminate\Http\JsonResponse;
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
			#'mystery_boxes.product_id',
			#'mystery_boxes.variant_id',
			#'variants.title as variant_title',
		]);
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
		]);
		$query->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id');
		$query->leftJoin('products', 'products.product_id', '=', 'mystery_boxes.product_id');
		$query->leftJoin('variants', 'variants.variant_id', '=', 'mystery_boxes.variant_id');
		$query->where(['mystery_boxes.packed' => 0, 'mystery_boxes.selected' => 1]);
		$query->orderBy('mystery_boxes.order_id');

		$mystery_boxes = $query->get()->toArray();
		#dump($mystery_boxes);

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


		$res = $this->createPDF($order_id, $line_id);
		Log::stack(['custom'])->debug($res);

		/*MysteryBox::where([
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

		$order = Order::whereOrderId($order_id);
		$order->delete();*/

		$error = 1;

		return response()->json(compact('error'));
	}

	// Generate PDF
	private function createPDF($order_id, $line_id) {
		$mystery_boxes = MysteryBox::where([
			'order_id' => $order_id,
			'line_id' => $line_id,
		])->get()->toArray();

		// share data to view
		#view()->share('employee', $data);

		if(!is_dir(public_path('downloads')))
			mkdir(public_path('downloads'), 0755);

		$save_file = public_path(sprintf('%s%s%d_%s.pdf', 'downloads', DIRECTORY_SEPARATOR, $order_id, 'warehouse'));

		PDF::setOption(['chroot' => __DIR__]);
		$pdf = PDF::loadView('warehouse.pdf', ['mystery_boxes' => $mystery_boxes]);
		$pdf->save($save_file);

		return $save_file;
	}
}

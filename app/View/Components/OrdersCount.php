<?php

namespace App\View\Components;

use App\Models\MysteryBox;
use Illuminate\View\Component;
use App\Models\Order;

class OrdersCount extends Component{

	public string $type;

	/**
	 * Create the component instance.
	 *
	 * @param string $type
	 * @return void
	 */
	public function __construct($type){
		$this->type = $type;
		#$this->count = $count;

	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render(){
		$count = 0;
		$css_class = 'js_orders_on_'.$this->type;
		
		switch($this->type){
			case "shop_1":
				/*$count = Order::where(['shop_id' => 1, 'is_mystery_box' => 1])
					->whereNull('fulfillment_status')
					->orWhere('fulfillment_status', '!=', 'fulfilled')
					->count();*/
				$count = MysteryBox::where(['mystery_boxes.finished' => 0])
					->leftJoin('orders', 'orders.order_id', '=', 'mystery_boxes.order_id')
					->where(['orders.is_mystery_box' => 1])
					->whereNull('orders.deleted_at')
					->whereNull('orders.fulfillment_status')
					->orWhere('orders.fulfillment_status', '!=', 'fulfilled')
					->count();
				break;
			case "shop_2":
				$count = Order::where(['shop_id' => 2, 'is_mystery_box' => 1])
					->whereNull('fulfillment_status')
					->orWhere('fulfillment_status', '!=', 'fulfilled')
					->count();
				break;
			case "shop_3":
				$count = Order::where(['shop_id' => 3, 'is_mystery_box' => 1])
					->whereNull('fulfillment_status')
					->orWhere('fulfillment_status', '!=', 'fulfilled')
					->count();
				break;
			default:
				break;
		}

		return view('components.orders-count', compact('count', 'css_class'));
	}
}

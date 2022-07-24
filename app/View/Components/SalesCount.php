<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Sales;

class SalesCount extends Component{

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
		$css_class = 'js_sales_on_'.$this->type;
		
		switch($this->type){
			case "shopify":
				$count = Sales::where(['shop_source' => 'shopify'])->count();
				break;
			case "depop":
				$count = Sales::where(['shop_source' => 'depop'])->count();
				break;
			case "asos":
				$count = Sales::where(['shop_source' => 'asos'])->count();
				break;
			default:
				break;
		}

		return view('components.sales-count', compact('count', 'css_class'));
	}
}

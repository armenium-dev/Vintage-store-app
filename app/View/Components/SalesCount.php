<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Sales;

class SalesCount extends Component{

	public string $type;
	public int $count;

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
		switch($this->type){
			case "shopify":
				$this->count = Sales::where(['shop_source' => 'shopify'])->count();
				break;
			case "depop":
				$this->count = Sales::where(['shop_source' => 'depop'])->count();
				break;
			case "asos":
				$this->count = Sales::where(['shop_source' => 'asos'])->count();
				break;
			default:
				break;
		}

		return view('components.sales-count', ['count' => $this->count]);
	}
}

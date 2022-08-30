<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\MysteryBox;
use Illuminate\Support\Facades\DB;

class WarehouseCount extends Component{
	
	public string $type;
	
	/**
	 * Create the component instance.
	 *
	 * @param string $type
	 * @return void
	 */
	public function __construct($type){
		$this->type = $type;
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render(){
		$count = 0;
		
		switch($this->type){
			case "pick":
				$count = MysteryBox::where(['selected' => 0, 'packed' => 0])->count();
				break;
			case "pack":
				$result = MysteryBox::where(['selected' => 1, 'packed' => 0])
                    ->select('order_id')->groupBy('order_id')->get();
				$count = $result->count();
				break;
			default:
				break;
		}
		
		return view('components.warehouse-count', compact('count'));
	}
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\MysteryBox;

class WarehouseCount extends Component{

	/**
	 * Create the component instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render(){
		$count = MysteryBox::where(['selected' => 0, 'packed' => 0])->count();

		return view('components.warehouse-count', compact('count'));
	}
}

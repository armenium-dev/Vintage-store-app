<?php

namespace App\Http\Controllers;

use App\Models\CustomProduct;
use App\Http\Requests\StoreCustomProductRequest;
use App\Http\Requests\UpdateCustomProductRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class CustomProductsController extends Controller {

	public $categories = ['Category 1', 'Category 2', 'Category 3'];
	public $sizes = ['2XL', '3XL', 'Large', 'Medium', 'Small', 'XLarge'];

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Application|Factory|View
	 */
	public function index(): View|Factory|Application{
		$products = CustomProduct::orderByDesc('id')->get();

		return view('custom-products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Application|Factory|View
	 */
	public function create(): View|Factory|Application{
		return view('custom-products.create', ['categories' => $this->categories, 'sizes' => $this->sizes]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \App\Http\Requests\StoreCustomProductRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(StoreCustomProductRequest $request){
		CustomProduct::create($request->authorize());

		return redirect()->route('custom-products.index')->with('status', 'Custom Product Created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\Response
	 */
	public function show(CustomProduct $customProduct){}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @return Application|Factory|View
	 */
	public function edit(CustomProduct $customProduct){
		return view('custom-products.edit', ['product' => $customProduct, 'categories' => $this->categories, 'sizes' => $this->sizes]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \App\Http\Requests\UpdateCustomProductRequest $request
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateCustomProductRequest $request, CustomProduct $customProduct){
		#CustomProduct::update($request->authorize());

		$customProduct->update($request->authorize());

		return redirect()->route('custom-products.index')->with('status', 'Custom Product Updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function destroy(CustomProduct $customProduct, Request $request){
		$customProduct->delete();
		$error = $customProduct->trashed() ? 0 : 1;

		if($request->type == 'ajax'){
			return response()->json(compact('error'));
		}else{
			return redirect()->route('custom-products.index')->with('status', 'Custom Product Deleted');
		}
	}
}

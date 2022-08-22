<?php

namespace App\Http\Controllers;

use App\Models\CustomProduct;
use App\Http\Requests\StoreCustomProductRequest;
use App\Http\Requests\UpdateCustomProductRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class CustomProductsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Application|Factory|View
	 */
	public function index(): View|Factory|Application{
		$products = CustomProduct::all();

		return view('custom-products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Application|Factory|View
	 */
	public function create(): View|Factory|Application{
		return view('custom-products.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \App\Http\Requests\StoreCustomProductRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCustomProductRequest $request){
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\Response
	 */
	public function show(CustomProduct $customProduct){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\Response
	 */
	public function edit(CustomProduct $customProduct){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \App\Http\Requests\UpdateCustomProductRequest $request
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateCustomProductRequest $request, CustomProduct $customProduct){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\CustomProduct $customProduct
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(CustomProduct $customProduct){
		//
	}
}

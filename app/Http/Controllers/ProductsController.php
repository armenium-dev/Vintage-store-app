<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Shopify\Utils;
use Shopify\Rest\Admin2022_07\Collect;
use App\Http\Shopify\MyShopify;
use \App\Http\Controllers\TagsController;

class ProductsController extends Controller {

	private TagsController $TagsController;

	public function __construct(TagsController $TC){
		$this->TagsController = $TC;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product){
        //
    }

	public function updateOrCreate($data){

		if(isset($data['tags'])){
			$this->TagsController->renewTags($data['shop_id'], $data['product_id'], $data['tags']);
			unset($data['tags']);
		}

		Product::updateOrCreate(
			['shop_id' => $data['shop_id'], 'product_id' => $data['product_id']],
			$data
		);

	}
}

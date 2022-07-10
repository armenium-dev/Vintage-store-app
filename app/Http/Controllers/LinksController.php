<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Product;
use Illuminate\Http\Request;

class LinksController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index(){
		$shop_1_links = Link::where(['shop_id' => 1])->orderByDesc('updated_at')->get();
		$shop_2_links = Link::where(['shop_id' => 2])->orderByDesc('updated_at')->get();
		$shop_3_links = Link::where(['shop_id' => 3])->orderByDesc('updated_at')->get();

		return view('links.index', compact('shop_1_links', 'shop_2_links', 'shop_3_links'));
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
	 * @param \App\Models\Link $link
	 * @return \Illuminate\Http\Response
	 */
	public function show(Link $link){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Link $link
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Link $link){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Link $link
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Link $link){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Link $link
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Link $link){
		//
	}

	public function remove(Request $request){
		$model = Link::find($request->post('id'));
		$model->delete();

		$error = $model->trashed() ? 0 : 1;

		return response()->json(compact('error'));
	}

}

<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;

class UploadsController extends Controller {

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function index(){
		return view('uploader.index', []);
	}

	/**
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadCsvFiles(UploadRequest $request){
		$depopCsvFile_path = $request->file('depopCsvFile')->store('uploads');
		$asosCsvFile_path = $request->file('asosCsvFile')->store('uploads');
		dd([$depopCsvFile_path, $asosCsvFile_path]);

		$nameImage = str_replace(" ","-",$getProduct->title);
		$svg = $nameImage.'-'.time();
		$depopCsvFile->move(public_path('uploads'), $svg.'.svg');

		$request->merge(['name' => $getProduct->title,'url_svg'=>$svg]);
		$product = Product::create($request->all());

		return redirect('builder/'.$product->id)->with('status', 'Jersey created');
	}


}

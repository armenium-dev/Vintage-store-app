<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Uploads;
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
		return view('uploads.index', []);
	}
	/**
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
	 */
	public function result(){
		return view('uploads.result', []);
	}

	/**
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function uploadFiles(UploadRequest $request){
		$depopFile_path = $request->file('depopFile')->store('uploads');
		$asosFile_path = $request->file('asosFile')->store('uploads');

		$depopFile_extension = pathinfo($depopFile_path, PATHINFO_EXTENSION);
		$asosFile_extension = pathinfo($asosFile_path, PATHINFO_EXTENSION);


		Uploads::create(['file' => $depopFile_path, 'file_type' => $depopFile_extension]);
		Uploads::create(['file' => $asosFile_path, 'file_type' => $asosFile_extension]);

		return redirect(route('uploadResult'))->with('status', 'Files uploaded successfully!');
	}


}

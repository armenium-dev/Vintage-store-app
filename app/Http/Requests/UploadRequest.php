<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(){
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(){
		return [
			'depopCsvFile' => 'required|file|mimes:csv|max:20480',
			'asosCsvFile' => 'required|file|mimes:csv|max:20480',
		];
	}

	public function messages(){
		return [
			'depopCsvFile.required' => 'An Image is required',
			'depopCsvFile.image' => 'File Must be an image',
			'depopCsvFile.mimes' => 'The Image must be an extension SVG',
		];
	}
}

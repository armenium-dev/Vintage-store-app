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
			'depopFile' => 'required|file|mimes:csv|max:20480',
			'asosFile' => 'required|file|mimes:html|max:20480',
		];
	}

	public function messages(){
		return [
			'depopFile.required' => 'An CSV is required',
			'depopFile.file' => 'File Must be an CSV',
			'depopFile.mimes' => 'The CSV must be an extension CSV',
			'asosFile.required' => 'An HTML is required',
			'asosFile.file' => 'File Must be an HTML',
			'asosFile.mimes' => 'The HTML must be an extension HTML',
		];
	}
}

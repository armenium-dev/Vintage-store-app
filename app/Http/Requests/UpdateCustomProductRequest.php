<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomProductRequest extends CustomProductRequest{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return array
	 */
	public function authorize(){
		return $this->requestData();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules(){
		return [
			'image' => 'file|mimes:jpg,jpeg,png|max:20480',
		];
	}

	public function messages(){
		return [
			#'image.required' => 'An Image is required',
			'image.file' => 'File Must be an jpg,jpeg or png',
			'image.mimes' => 'The Image must be an extension jpg,jpeg or png',
		];
	}

}

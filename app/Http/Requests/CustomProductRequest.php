<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomProductRequest extends FormRequest{

	public function requestData(){
		$data = $this->input();

		if($this->file('image')){
			$file_path = $this->file('image')->store('images', 'public');

			$data['image'] = $file_path;
		}

		return $data;
	}

}

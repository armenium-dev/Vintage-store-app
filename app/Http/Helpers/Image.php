<?php

namespace App\Http\Helpers;

class Image{

	public static function letProductThumb($image, $size = 200){

		if(!is_null($image) && $size > 0){
			if(str_contains($image, '?')){
				$image = explode('?', $image)[0];
			}

			$info = pathinfo($image);

			$image = sprintf('%s/%s_%dx.%s', $info['dirname'], $info['filename'], $size, $info['extension']);
		}

		return $image;
	}

}

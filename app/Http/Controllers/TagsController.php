<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagsController extends Controller {

	public function addTags($shop_id, $product_id, $tags){
		if(!empty($tags)){
			foreach($tags as $tag){
				Tag::create(['shop_id' => $shop_id, 'product_id' => $product_id, 'tag' => $tag]);
			}
		}
	}

	public function renewTags($shop_id, $product_id, $tags){
		Tag::where(['shop_id' => $shop_id, 'product_id' => $product_id])
			->delete();

		$this->addTags($shop_id, $product_id, $tags);
	}

	public function parseAndAddProductTags($shop_id, $product_id, string $tags){

	}

	public function parseProductTags(string $tags): array{
		$res = ['link_asos' => '', 'link_depop' => '', 'tags' => []];

		if(empty($tags)) return $res;

		$a = array_map('trim', explode(',', $tags));

		$another_tags = [];

		foreach($a as $v){
			$add_other = true;

			if($v != 'NOTASOS' && strstr($v, 'asos') !== false){
				$res['link_asos'] = $v;
				$add_other = false;
			}
			if($v != 'NOTDEPOP' && strstr($v, 'depop') !== false){
				$res['link_depop'] = $v;
				$add_other = false;
			}

			if($add_other){
				$another_tags[] = $v;
			}
		}

		if(!empty($another_tags)){
			$res['tags'] = $another_tags;
		}

		return $res;
	}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webhooks extends Model {

    use HasFactory, SoftDeletes;

    protected $fillable = [
		'name',
		'shop_id',
		'webhook_id',
		'active',
		'topic',
		'address',
		'fields',
		'format',
	];

    public static function getByName($name){
        $q = self::where(['name' => $name])->select('value')->first();

        return ($q) ? $q->value : '';
    }

    public static function getLike($fragment){
        $response = [];

        $q = self::where('name', 'like', '%'.$fragment.'%')->where('active', 1)->get();

        if(!is_null($q))
            foreach($q as $v)
                $response[$v->name] = $v->value;

        return $response;
    }

	public static function getHooks($active = true){

		$q = self::where('active', ($active ? 1 : 0))->get();

		return $q->toArray();
	}

}

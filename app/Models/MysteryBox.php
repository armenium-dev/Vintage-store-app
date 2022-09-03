<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MysteryBox extends Model{
	use HasFactory, SoftDeletes;

	protected $table = 'mystery_boxes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'order_id',
		'line_id',
		'finished',
		'pdf_file',
	];

	public function productName(): string{
		#dd($this->line_id);
		#return $this->data['line_items'][0]['title'];

		$data = json_decode($this->data, true);

		$res = '';

		if(!empty($data)){
			$titles = [];
			foreach($data['line_items'] as $item){
				if($item['id'] == $this->line_id){
					$titles[] = '<b>'.$item['title'].'</b>';
					$titles[] = '<small>'.$item['variant_title'].'</small>';
				}
			}
			$res = implode('<br/>', $titles);
		}

		return $res;
	}

}

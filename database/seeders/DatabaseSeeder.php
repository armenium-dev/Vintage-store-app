<?php

namespace Database\Seeders;

use App\Models\Variant;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder{

	public $truncate = true;

	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run(){
		$logs = [];

		if($this->truncate){
			try {
				#DB::statement('SET FOREIGN_KEY_CHECKS = 0');
				Variant::truncate();
				#DB::statement('SET FOREIGN_KEY_CHECKS = 1');
			}catch(\Exception $e){
				foreach(Variant::all() as $variant)
					$variant->delete();
			}
		}

		$variants = Product::where('variant_id', '!=', 0)->get();

		foreach($variants as $variant){
			$model = Variant::create([
				'shop_id'  => $variant->shop_id,
				'product_id'  => $variant->product_id,
				'variant_id'  => $variant->variant_id,
				'title'  => $variant->title,
				'inventory_quantity'  => $variant->qty,
				#'price'  => $variant->price,
				'created_at'  => $variant->created_at,
				'updated_at'  => $variant->updated_at,
			]);
			$logs[] = sprintf('id: %d', $model->id);
		}

		dump($logs);

	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('variants', function(Blueprint $table){
			$table->id();
			$table->integer('shop_id')->default(0);
			$table->unsignedBigInteger('product_id')->default(0);
			$table->unsignedBigInteger('variant_id')->default(0);
			$table->string('title');
			$table->smallInteger('inventory_quantity')->default(0);
			$table->float('price')->default('0.00');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('variants');
	}
};

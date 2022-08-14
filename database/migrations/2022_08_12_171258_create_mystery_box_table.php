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
		Schema::create('mystery_boxes', function(Blueprint $table){
			$table->id();
			$table->unsignedBigInteger('order_id')->default(0);
			$table->unsignedBigInteger('line_id')->default(0);
			$table->unsignedBigInteger('product_id')->default(0);
			$table->unsignedBigInteger('variant_id')->default(0);
			$table->string('formula', 50)->nullable();
			$table->string('tag', 15)->nullable();
			$table->integer('sort_num_1')->default(0);
			$table->integer('sort_num_2')->default(0);
			$table->smallInteger('selected')->default(0);
			$table->smallInteger('packed')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('mystery_boxes');
	}
};

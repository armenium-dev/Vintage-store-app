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
		Schema::create('products_custom', function(Blueprint $table){
			$table->id();
			$table->string('title', 100)->nullable();
			$table->string('category', 100)->nullable();
			$table->string('size', 50)->nullable();
			$table->float('price')->default('0.00');
			$table->integer('count')->default(0);
			$table->string('image', 255)->nullable();
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
		Schema::dropIfExists('products_custom');
	}
};

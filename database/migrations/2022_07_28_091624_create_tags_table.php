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
		Schema::create('tags', function(Blueprint $table){
			$table->id();
			$table->integer('shop_id')->default(0);
			$table->unsignedBigInteger('product_id')->default(0);
			$table->string('tag', 255)->nullable();
			$table->timestamps();

			$table->unique(['shop_id', 'product_id', 'tag']);
			$table->fullText('tag');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('tags');
	}
};

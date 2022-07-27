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
		Schema::table('products', function(Blueprint $table){
			$table->dropColumn('variant_id');
			$table->dropColumn('qty');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::create('products', function(Blueprint $table){
			$table->unsignedBigInteger('variant_id')->default(0);
			$table->integer('qty')->default(0);
		});
	}
};

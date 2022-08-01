<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('products', function(Blueprint $table){
			$table->smallInteger('is_mystery')->default(0)->after('product_id');
			$table->json('options')->nullable()->after('body');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('products', function(Blueprint $table){
			$table->dropColumn('is_mystery');
			$table->dropColumn('options');
		});
	}
};

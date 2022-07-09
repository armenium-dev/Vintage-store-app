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
		Schema::table('links', function(Blueprint $table){
			$table->unsignedBigInteger('variant_id')->default(0)->after('shop_id');
			$table->unsignedBigInteger('product_id')->default(0)->after('shop_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('links', function(Blueprint $table){
			$table->dropColumn('product_id');
			$table->dropColumn('variant_id');
		});
	}
};

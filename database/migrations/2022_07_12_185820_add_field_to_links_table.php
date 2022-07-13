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
			$table->enum('shop_type', ['shopify', 'depop', 'asos', 'other_1', 'other_2', 'other_3'])->nullable()->after('link_asos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('links', function(Blueprint $table){
			$table->dropColumn('shop_type');
		});
	}
};

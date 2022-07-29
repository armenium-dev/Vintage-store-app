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
		Schema::table('orders', function(Blueprint $table){
			$table->smallInteger('is_mystery_box')->default(0)->after('order_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('orders', function(Blueprint $table){
			$table->dropColumn('is_mystery_box');
		});
	}
};

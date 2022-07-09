<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('products', function(Blueprint $table){
			$table->string('status', 15)->nullable()->change();
			$table->string('link_depop', 255)->nullable()->change();
			$table->string('link_asos', 255)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('products', function(Blueprint $table){
			//
		});
	}
};

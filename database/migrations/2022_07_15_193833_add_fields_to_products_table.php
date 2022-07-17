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
			$table->text('body')->nullable()->after('title');
			$table->string('p_updated_at', 25)->nullable()->after('link_asos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('products', function(Blueprint $table){
			$table->dropColumn('body');
			$table->dropColumn('p_updated_at');
		});
	}
};

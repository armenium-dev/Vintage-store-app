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
			$table->string('pdf_file', 255)->nullable()->after('data');
			$table->smallInteger('finished')->default(0)->after('data');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('orders', function(Blueprint $table){
			$table->dropColumn('pdf_file');
			$table->dropColumn('finished');
		});
	}
};

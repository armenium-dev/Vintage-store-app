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
		Schema::table('uploads', function(Blueprint $table){
			$table->string('file_type')->nullable()->after('file');
			$table->integer('processed')->default(0)->after('parsed');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('uploads', function(Blueprint $table){
			$table->dropColumn('file_type');
			$table->dropColumn('processed');
		});
	}
};

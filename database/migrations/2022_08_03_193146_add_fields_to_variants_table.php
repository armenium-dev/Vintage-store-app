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
		Schema::table('variants', function(Blueprint $table){
			$table->string('option3', 20)->nullable()->after('title');
			$table->string('option2', 20)->nullable()->after('title');
			$table->string('option1', 20)->nullable()->after('title');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('variants', function(Blueprint $table){
			$table->dropColumn('option1');
			$table->dropColumn('option2');
			$table->dropColumn('option3');
		});
	}
};

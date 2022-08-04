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
		$_sql = file_get_contents(database_path('/sql/view_vintage_items.sql'));
		DB::statement('DROP VIEW IF EXISTS vintage_items');
		DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		DB::statement('DROP VIEW IF EXISTS vintage_items');
	}
};

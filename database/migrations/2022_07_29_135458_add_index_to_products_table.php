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
			$table->index('shop_id');
			$table->index('product_id');
			$table->index('title');
			$table->fullText('body');
			$table->index('status');
			$table->index('link_depop');
			$table->index('link_asos');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('products', function(Blueprint $table){
			$table->dropIndex('shop_id');
			$table->dropIndex('product_id');
			$table->dropIndex('title');
			$table->dropIndex('body');
			$table->dropIndex('status');
			$table->dropIndex('link_depop');
			$table->dropIndex('link_asos');
		});
	}
};

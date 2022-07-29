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
			$table->index('shop_id');
			$table->index('order_id');
			$table->index('payment_status');
			$table->index('fulfillment_status');
			#$table->index('data');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('orders', function(Blueprint $table){
			$table->dropIndex('shop_id');
			$table->dropIndex('order_id');
			$table->dropIndex('payment_status');
			$table->dropIndex('fulfillment_status');
			#$table->dropIndex('data');
		});
	}
};

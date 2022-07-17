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
		Schema::create('webhooks', function(Blueprint $table){
			$table->id();
			$table->string('name', 100)->nullable();
			$table->integer('shop_id')->default(0);
			$table->bigInteger('webhook_id')->default(0);
			$table->smallInteger('active')->default(0);
			$table->string('topic', 50)->nullable();
			$table->string('address', 255)->nullable();
			$table->json('fields')->nullable();
			$table->string('format', 10)->default('json');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('webhooks');
	}
};

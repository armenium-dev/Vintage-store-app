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
		Schema::create('mystery_boxes', function(Blueprint $table){
			$table->id();
			$table->unsignedBigInteger('order_id')->default(0);
			$table->unsignedBigInteger('line_id')->default(0);
			$table->smallInteger('finished')->default(0);
			$table->string('pdf_file', 255)->nullable();
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
		Schema::dropIfExists('mystery_boxes');
	}
};

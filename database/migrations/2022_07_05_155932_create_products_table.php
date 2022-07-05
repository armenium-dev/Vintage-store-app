<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('products', function(Blueprint $table){
            $table->id();
            $table->integer('shop_id')->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->unsignedBigInteger('variant_id')->default(0);
            $table->string('title');
            $table->integer('qty')->default(0);
            $table->string('status');
            $table->string('link_depop');
            $table->string('link_asos');
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
        Schema::dropIfExists('products');
    }
};

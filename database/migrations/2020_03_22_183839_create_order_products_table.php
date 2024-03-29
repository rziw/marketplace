<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_name');
            $table->bigInteger('shop_id')->unsigned();
            $table->integer('count')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('discount')->nullable();
            $table->integer('description')->nullable();
            $table->integer('status')->nullable();//null or deleted
            $table->timestamps();

            //relations
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}

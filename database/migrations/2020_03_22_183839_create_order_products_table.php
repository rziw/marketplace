<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_name');
            $table->integer('seller_id')->unsigned();
            $table->integer('count')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('discount')->nullable();
            $table->integer('description')->nullable();

            $table->timestamps();
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

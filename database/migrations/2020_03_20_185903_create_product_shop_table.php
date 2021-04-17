<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shop', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('count')->default(0);
            $table->integer('price');
            $table->float('rate')->default(0);
            $table->string('discount')->nullable();
            $table->string('color')->nullable();
            $table->boolean('has_guarantee')->default(1);
            $table->string('guarantee_description')->nullable();
            $table->text('extra_description')->nullable();
            $table->string('status')->default('waiting');

            $table->timestamps();

            //relations
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_shop');
    }
}

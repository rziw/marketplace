<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('status')->default('waiting');//statuses are waiting or accepted

            $table->timestamps();

            //relations
            $table->foreign('shop_id')->references('id')->on('shops')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('product_shop');
        Schema::enableForeignKeyConstraints();
    }
}

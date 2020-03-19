<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('main_image')->nullable();
            $table->string('status')->nullable();
            $table->text('tag')->nullable();
            $table->boolean('hide')->default(0);
            $table->integer('count')->default(0);
            $table->float('vote')->default(0);
            $table->integer('price');
            $table->string('discount')->nullable();
            $table->string('color')->nullable();
            $table->integer('shop_id')->unsigned();
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
        Schema::dropIfExists('products');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('home_number')->nullable();
            $table->integer('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->text('province')->nullable();
            $table->text('city')->nullable();
            $table->string('role')->default('customer');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('radius')->default(5);
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

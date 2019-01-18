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
            $table->increments('id');
            $table->string('email', 50)->unique();
            $table->string('password', 30);
            $table->string('name', 60);
            $table->unsignedInteger('dni')->nullable();
            $table->string('photo', 55)->nullable();
            $table->unsignedInteger('salary')->nullable();
            $table->unsignedBigInteger('phone')->nullable();
            $table->unsignedDecimal('comission')->nullable();
            $table->string('address', 100)->nullable();
            $table->string('role', 20);
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

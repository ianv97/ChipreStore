<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->unsignedInteger('province_id');
            
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
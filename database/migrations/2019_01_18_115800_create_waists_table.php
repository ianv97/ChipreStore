<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaistsTable extends Migration
{
    public function up()
    {
        Schema::create('waists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 55);
        });
    }

    public function down()
    {
        Schema::dropIfExists('waists');
    }
}
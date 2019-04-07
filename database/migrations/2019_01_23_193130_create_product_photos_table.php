<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('product_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 55);
            $table->unsignedInteger('product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_photos');
    }
}
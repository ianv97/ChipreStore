<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsWaistsTable extends Migration
{
    public function up()
    {
        Schema::create('products_waists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_quantity');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('waist_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('waist_id')->references('id')->on('waists');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products_waists');
    }
}
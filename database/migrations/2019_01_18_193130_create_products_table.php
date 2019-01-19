<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('name', 60);
            $table->string('description')->nullable();
            $table->string('photo', 55);
            $table->unsignedDecimal('cost_price')->nullable();
            $table->unsignedDecimal('sale_price');
            $table->unsignedDecimal('credit_price')->nullable();
            $table->integer('stock_quantity');
            $table->boolean('without_stock_sales');
            $table->unsignedInteger('category_id')->nullable();

            $table->foreign('category_id')->references('id')->on('categories');

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

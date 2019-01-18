<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableProducts extends Migration
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
            $table->string('description');
            $table->string('photo', 55);
            $table->unsignedDecimal('cost_price');
            $table->unsignedDecimal('sale_price');
            $table->unsignedDecimal('credit_price');
            $table->integer('stock_quantity');
            $table->boolean('without_stock_sales');
            $table->unsignedInteger('category_id');

            $table->foreign('category_id')->references('id')->on('categories')->ondelete('set null');

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

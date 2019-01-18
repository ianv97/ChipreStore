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
            $table->string('name');
            $table->string('description',240);
            $table->string('photo',240);
            $table->decimal('cost_price');
            $table->decimal('sale_price');
            $table->decimal('credit_price');
            $table->integer('stock_quantity');
            $table->boolean('without_stock_sales');
            $table->integer('category_id')->unsigned();

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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quantity');
            $table->unsignedDecimal('subtotal');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('waist_id')->nullable();
            $table->unsignedInteger('sale_id');
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('waist_id')->references('id')->on('waists')->onDelete('set null');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale__lines');
    }
}

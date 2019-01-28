<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration{
    public function up()
      {
          Schema::table('products', function($table) {
                $table->dropColumn('stock_quantity');
                $table->dropColumn('photo');
                $table->boolean('visible');
                $table->dropForeign('products_category_id_foreign');
                $table->dropColumn('category_id');
          });
      }

      public function down()
      {
          Schema::table('products', function($table) {
                $table->integer('stock_quantity');
                $table->string('photo', 55);
                $table->dropColumn('visible');
                $table->unsignedInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('categories');
          });
      }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersTable extends Migration{
    public function up(){
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('products_offer_id')->references('offer_id')->on('products_offers')->ondelete('cascade');
        });
    }
}
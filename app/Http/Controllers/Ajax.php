<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

Class Ajax extends Controller {
    function find_product() {
        $product = DB::table('products')
                ->leftJoin('product_photos','product_photos.product_id', '=', 'products.id')
                ->leftJoin('categories_products','categories_products.product_id', '=', 'products.id')
                ->leftJoin('categories','categories_products.category_id', '=', 'category.id')
                ->where('products.id', $_POST['product_id'])->first();
        return response()->json_encode($product);
    }
}
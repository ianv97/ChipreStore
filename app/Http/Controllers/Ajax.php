<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Class Ajax extends Controller {
    function find_product() {
        $product = \DB::table('products')
                ->where('products.id', $_POST['product_id'])
                ->select('name', 'description', 'cost_price', 'sale_price',
                        'credit_price', 'without_stock_sales', 'visible')
                ->first();
        $categories = \DB::table('categories_products')
                ->join('categories', 'categories.id', '=', 'categories_products.category_id')
                ->where('categories_products.product_id', $_POST['product_id'])
                ->select('categories.id')
                ->get();
        $waists = \DB::table('products_waists')
                ->join('waists', 'waists.id', '=', 'products_waists.waist_id')
                ->where('products_waists.product_id', $_POST['product_id'])
                ->select('waists.id', 'products_waists.stock_quantity')
                ->get();
        $photos = \DB::table('product_photos')
                ->where('product_photos.product_id', $_POST['product_id'])
                ->select('name')
                ->get();
        return response()->json(array('product'=>$product, 'categories'=>$categories, 'waists'=>$waists, 'photos'=>$photos));
    }
}
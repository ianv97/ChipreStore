<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Class AjaxController extends Controller {
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
    
    function find_cities(){
        $cities = \DB::table('cities')
                ->where('province_id', $_POST['province_id'])
                ->select('id', 'name')
                ->get();
        return response()->json($cities);
    }
    
    function find_sale_lines(){
        $sale_lines = \DB::table('sales')->join('sale_lines', 'sales.id', '=', 'sale_lines.sale_id')
                        ->join('waists', 'sale_lines.waist_id', '=', 'waists.id')
                        ->join('products', 'sale_lines.product_id', '=', 'products.id')
                        ->join('product_photos', 'product_photos.product_id', '=', 'products.id')
                        ->select('sales.total', 'sale_lines.quantity as qty', 'sale_lines.subtotal', 'products.name as product', 'waists.name as waist', 'product_photos.name as photo')
                        ->whereRaw('product_photos.name IN (SELECT MIN(product_photos.name) FROM product_photos GROUP BY product_photos.product_id)')
                        ->where('sale_lines.sale_id', $_POST['sale_id'])->get();
        return response()->json($sale_lines);
    }
    
    function find_purchase_lines(){
        if (!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['role'])){
            if ($_SESSION['role'] = 'Cliente'){
                $purchase_lines = \DB::table('sales')->join('sale_lines', 'sales.id', '=', 'sale_lines.sale_id')
                        ->join('waists', 'sale_lines.waist_id', '=', 'waists.id')
                        ->join('products', 'sale_lines.product_id', '=', 'products.id')
                        ->join('product_photos', 'product_photos.product_id', '=', 'products.id')
                        ->select('sales.total', 'sale_lines.quantity as qty', 'sale_lines.subtotal', 'products.name as product', 'waists.name as waist', 'product_photos.name as photo')
                        ->whereRaw('product_photos.name IN (SELECT MIN(product_photos.name) FROM product_photos GROUP BY product_photos.product_id)')
                        ->where('sale_lines.sale_id', $_POST['sale_id'])
                        ->where('sales.customer_id', $_SESSION['id'])->get();
                return response()->json($purchase_lines);
            }else{
                return response(action('SessionController@login'));
            }
        }else{
            return response(action('SessionController@login'));
        }
    }
}
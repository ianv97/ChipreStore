<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Shop extends Controller
{
    function index(){
        return view('index');
    }
    
    function products(){
        return view('products');
    }
    
    function product_details($id){
        $product = \App\Product::find($id);
        return view('product_details', compact('product'));
    }
    
    function cart(){
        return view('cart');
    }
    
    function checkout(){
        return view('checkout');
    }
}
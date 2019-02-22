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
    
    function add_to_cart(){
        if (!isset($_SESSION)){
            session_start();
        }
        setcookie("product[".$_SESSION['product']."][id]", $_SESSION['product'], time()+3600);
        setcookie("product[".$_SESSION['product']."][waist]", $_POST['waist'], time()+3600);
        setcookie("product[".$_SESSION['product']."][qty]", $_POST['qty'], time()+3600);
        return redirect()->action('Shop@cart');
    }
}
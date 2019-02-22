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
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][id]", $_SESSION['product'], time()+60);
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][waist]", $_POST['waist'], time()+60);
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][qty]", $_POST['qty'], time()+60);
        return redirect()->action('Shop@cart');
    }
}
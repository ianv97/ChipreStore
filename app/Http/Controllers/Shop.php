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
    
    function order(){
        if (!isset($_SESSION)){
            session_start();
        }
        \DB::transaction(function(){
            $data = request()->validate([
                'product_id' => 'required',
                'waist_id' => 'required',
                'qty' => 'required',
                'subtotal' => 'required',
                'total' => 'required'
            ]);
            
            \App\Sale::Create([
                'date' => date("Y-m-d"),
                'total' => $data['total'],
                'customer_id' => $_SESSION['id']
            ]);
            $sale_id = \DB::getPdo()->lastInsertId();
            
            \App\SaleLine::Create([
                'product_id' => $data['product_id'],
                'waist_id' => $data['waist_id'],
                'quantity' => $data['qty'],
                'sale_id' => $sale_id,
                'subtotal' => $data['subtotal']
            ]);
        });
        return redirect(action('Shop@cart'))->with('success', 'Su pedido fue registrado exitosamente');;;
    }
}
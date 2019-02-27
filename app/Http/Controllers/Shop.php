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
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Cliente'){
            \DB::transaction(function(){
                $data = request()->validate([
                    'product_id' => 'required',
                    'waist_id' => 'required',
                    'qty' => 'required',
                    'subtotal' => 'required',
                    'total' => 'required',
                    'city' => 'required',
                    'address' => 'required'
                ], [
                    'product_id.required' => 'Debe agregar un producto al carrito',
                    'waist_id.required' => 'Debe agregar un producto al carrito',
                    'qty.required' => 'Debe agregar un producto al carrito',
                    'subtotal.required' => 'Debe agregar un producto al carrito',
                ]);
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                \App\Sale::Create([
                    'date' => date("Y-m-d H:i:s"),
                    'state' => 'Envío pendiente',
                    'city_id' => $data['city'],
                    'address' => $data['address'],
                    'total' => $data['total'],
                    'customer_id' => $_SESSION['id']
                ]);
                $sale_id = \DB::getPdo()->lastInsertId();
                for ($i=0; $i<count($data['product_id']); $i++){
                    \App\SaleLine::Create([
                        'product_id' => $data['product_id'][$i],
                        'waist_id' => $data['waist_id'][$i],
                        'quantity' => $data['qty'][$i],
                        'sale_id' => $sale_id,
                        'subtotal' => $data['subtotal'][$i]
                    ]);
                }
            });
            return redirect(action('CustomerController@list_purchases'))->with('success', 'Su compra fue registrada exitosamente');;;
        }else{
            return redirect(action('Session@login'));
        }
        
    }
}
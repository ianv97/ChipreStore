<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Shop extends Controller
{
    function index(){
        $categories = \DB::select(\DB::raw('SELECT categories.id as cid, categories.name AS cname, MIN(products.sale_price) AS price,
(SELECT product_photos.name FROM product_photos
INNER JOIN products ON product_photos.product_id = products.id
INNER JOIN categories_products ON categories_products.product_id = products.id
WHERE categories_products.category_id = cid
AND products.visible = 1
ORDER BY RAND() LIMIT 1) AS photo
FROM categories
INNER JOIN categories_products ON categories.id = categories_products.category_id
INNER JOIN products ON products.id = categories_products.product_id
INNER JOIN product_photos ON products.id = product_photos.product_id
WHERE categories.state = 1 AND products.visible =1
GROUP BY cid, cname;'));
        return view('index')->with('categories', $categories);
    }
    
    function products($category_filter = null){
        $categories = \App\Category::where('state', 1)->get();
        $waists = \App\Waist::all();
        $products = \App\Product::where('visible', 1)->paginate(15);
        return view('products')->with('categories', $categories)->with('waists', $waists)->with('products', $products)->with('category_filter', $category_filter);
    }
    
    function product_details($id){
        $product = \App\Product::find($id);
        return view('product_details')->with('product', $product);
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
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][id]", $_SESSION['product'], time()+2592000, '/');
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][waist]", $_POST['waist'], time()+2592000, '/');
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][qty]", $_POST['qty'], time()+2592000, '/');
        return redirect()->action('Shop@cart');
    }
    
    function order(){
        if (!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Cliente'){
            $data = request()->validate([
                'product_id' => 'required',
                'product_name' => 'required',
                'product_description' => 'required',
                'product_photo' => 'required',
                'waist_id' => 'required',
                'waist_name' => 'required',
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
            $sale_id = 0;
            \DB::transaction(function() use($data, &$sale_id) {
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                \App\Sale::Create([
                    'date' => date("Y-m-d H:i:s"),
                    'state' => 'Pago pendiente',
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
            
            //Clear cookies
            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    if ($name != 'PHPSESSID'){
                        setcookie($name, '', 1, '/');
                    }
                }
            }


            \MercadoPago\SDK::setClientId("8711601650478762");
            \MercadoPago\SDK::setClientSecret("NRQVorG1CfpJU74qRwdkUAtoLdejoPDt");
            $preference = new \MercadoPago\Preference();

            for ($i=0; $i<count($data['product_id']); $i++){
                $item = new \MercadoPago\Item();
                $item->id = $data['product_id'][$i];
                $item->title = $data['product_name'][$i] . ' - Talle ' . $data['waist_name'][$i];
                $item->picture_url = $data['product_photo'][$i];
                $item->description = $data['product_description'][$i];
                $item->quantity = $data['qty'][$i];
                $item->currency_id = "ARS";
                $item->unit_price = $data['subtotal'][$i]/$data['qty'][$i];
                $items[] = $item;
            }

            $customer_name = explode(',', $_SESSION['name']);
            $customer_name[1] = substr($customer_name[1], 1);
            $payer = new \MercadoPago\Payer();
            $payer->surname = $customer_name[0];
            $payer->name = $customer_name[1];
            $payer->email = 'test_user_59681935@testuser.com'; //$_SESSION['email'];

            $preference->items = $items;
            $preference->payer = $payer;
            $preference->payment_methods = array(
                "excluded_payment_types" => array(
                    array("id" => "ticket"),
                    array("id" => "atm")
                )
            );
            $preference->back_urls = array(
                "success" => "http://chipre.test/success",
                "failure" => "http://chipre.test/success",
                "pending" => "http://chipre.test/success"
            );
            $preference->auto_return = "approved";
            $preference->binary_mode = true;
            $preference->external_reference = $sale_id;
            $preference->save();
            $sale = \App\Sale::find($sale_id);
            $sale->payment_link = $preference->init_point;
            $sale->save();
            return redirect($preference->init_point);
        }else{
            return redirect(action('Session@login'));
        }
        
    }
    
    function empty_cart(){
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                if ($name != 'PHPSESSID'){
                    setcookie($name, '', 1, '/');
                }
            }
        }
        return redirect(action('Shop@cart'));
    }
    
//    function create_test_user(){
//        \MercadoPago\SDK::setAccessToken("TEST-7185680297116601-022718-9272dccdec5f8159fa7ae48a8e495314-62040712");
//        $body = array(
//            "json_data" => array(
//              "site_id" => "MLA"
//            )
//        );
//        $result = \MercadoPago\SDK::post('/users/test_user', $body);
//
//        var_dump($result);
//    }
    
    function approve_payment(){
        \MercadoPago\SDK::setClientId("8711601650478762");
        \MercadoPago\SDK::setClientSecret("NRQVorG1CfpJU74qRwdkUAtoLdejoPDt");
        
        $merchant_order = \MercadoPago\MerchantOrder::find_by_id($_GET["merchant_order_id"]);
        $paid_amount = 0;
        foreach ($merchant_order->payments as $payment) {
            if ($payment->status == 'approved'){
                $paid_amount += $payment->transaction_amount;
            }
        }

        if($paid_amount >= $merchant_order->total_amount){
            $sale = \App\Sale::find($_GET["external_reference"]);
            $sale->state = 'Envío pendiente';
            $sale->payment_link = null;
            $sale->save();
            foreach ($sale->sale_lines as $sale_line){
                $product_waist = \App\ProductsWaist::where('product_id', $sale_line->product_id)->where('waist_id', $sale_line->waist_id)->first();
                $product_waist->stock_quantity -= $sale_line->quantity;
                $product_waist->save();
            }
            return redirect(action('CustomerController@list_purchases'))->with('success', 'Su compra fue procesada exitosamente. En breve le enviaremos sus productos.');
        }else{
            return redirect(action('CustomerController@list_purchases'))->withErrors(['payment' => 'No se pudo procesar el pago correctamente']);
        }
    }
}
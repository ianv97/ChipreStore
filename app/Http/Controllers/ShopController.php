<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
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
    
    function add_to_cart(){
        if (!isset($_SESSION)){
            session_start();
        }
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][id]", $_SESSION['product'], time()+2592000, '/');
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][waist]", $_POST['waist'], time()+2592000, '/');
        setcookie("product[".$_SESSION['product']."][".$_POST['waist']."][qty]", $_POST['qty'], time()+2592000, '/');
        return redirect()->action('ShopController@cart');
    }
    
    function order(){
        if (!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Cliente' and isset($_COOKIE["product"])){
            $data = request()->validate([
                'product_id' => 'required',
                'city' => 'required',
                'address' => 'required'
            ], [
                'product_id.required' => 'Debe agregar un producto al carrito'
            ]);
            
            $total = 0;
            foreach($_COOKIE["product"] as $cookie_product){
                foreach($cookie_product as $cookie_waist){
                    $product = \App\Product::find($cookie_waist['id']);
                    $discount = 0;
                    foreach($product->categories_products as $cat_prod){
                        foreach ($cat_prod->category->offers as $offer){
                           $discount += $offer->discount_percentage;
                        }
                    }
                    foreach ($product->products_offers as $product_offer){
                        $discount += $product_offer->offer->discount_percentage;
                    }
                    $waist_id = $cookie_waist['waist'];
                    $sale_products[$product->id][$waist_id]['product_id'] = $product->id;
                    $sale_products[$product->id][$waist_id]['product_name'] = $product->name;
                    $sale_products[$product->id][$waist_id]['product_description'] = $product->description;
                    $sale_products[$product->id][$waist_id]['product_photo'] = 'http://chipre.test/img/product-img/'.$product->photos[0]->name;
                    $sale_products[$product->id][$waist_id]['price'] = $product->sale_price - ($product->sale_price * ($discount/100));
                    $sale_products[$product->id][$waist_id]['waist_id'] = $waist_id;
                    $sale_products[$product->id][$waist_id]['waist_name'] = \App\Waist::find($cookie_waist['waist'])->name;
                    $sale_products[$product->id][$waist_id]['qty'] = $cookie_waist['qty'];
                    $sale_products[$product->id][$waist_id]['subtotal'] = $sale_products[$product->id][$waist_id]['price'] * $cookie_waist['qty'];
                    $total += $sale_products[$product->id][$waist_id]['subtotal'];
                }
            }
            
            \MercadoPago\SDK::setClientId("8711601650478762");
            \MercadoPago\SDK::setClientSecret("NRQVorG1CfpJU74qRwdkUAtoLdejoPDt");
            $preference = new \MercadoPago\Preference();
            
//            \DB::transaction(function() use($data, $sale_products, $total) {
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                \App\Sale::Create([
                    'date' => date("Y-m-d H:i:s"),
                    'state' => 'Pago pendiente',
                    'city_id' => $data['city'],
                    'address' => $data['address'],
                    'total' => $total,
                    'customer_id' => $_SESSION['id']
                ]);
                $sale_id = \DB::getPdo()->lastInsertId();
                foreach ($sale_products as $sale_product){
                    foreach ($sale_product as $product_waist){
                        \App\SaleLine::Create([
                            'product_id' => $product_waist['product_id'],
                            'waist_id' => $product_waist['waist_id'],
                            'quantity' => $product_waist['qty'],
                            'subtotal' => $product_waist['subtotal'],
                            'sale_id' => $sale_id
                        ]);
                        $item = new \MercadoPago\Item();
                        $item->id = $product_waist['product_id'];
                        $item->title = $product_waist['product_name'] . ' - Talle ' . $product_waist['waist_name'];
                        $item->description = $product_waist['product_description'];
                        $item->picture_url = $product_waist['product_photo'];
                        $item->quantity = $product_waist['qty'];
                        $item->currency_id = "ARS";
                        $item->unit_price = $product_waist['price'];
                        $items[] = $item;
                    }
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
                    "failure" => "http://chipre.test/purchases",
                    "pending" => "http://chipre.test/purchases"
                );
                $preference->auto_return = "approved";
                $preference->binary_mode = true;
                $preference->external_reference = $sale_id;
                $preference->expiration_date_from = date('c');
                $preference->expiration_date_to = date('c', strtotime('+7 days'));
                $preference->save();
                $sale = \App\Sale::find($sale_id);
                $sale->payment_link = $preference->init_point;
                $sale->save();
//            });
            
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
            
            return redirect($preference->init_point);
        }else{
            return redirect(action('SessionController@login'));
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
        return redirect(action('ShopController@cart'));
    }
    
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
    
    function discount_products(){
        $products = \App\Product::where('visible', 1)->paginate(15);
        return view('discount_products')->with('products', $products);
    }
    
    function new_products(){
        $products = \App\Product::where('visible', 1)->paginate(15);
        return view('new_products')->with('products', $products);
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
}
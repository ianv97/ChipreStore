<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Offer;
use App\Category;
use App\Product;

class OfferController extends Controller
{
    function list_offers(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            $offers = Offer::all();
            $categories = Category::all();
            $products = Product::all();
            return view('admin.offers_list')->with('offers',$offers)->with('categories',$categories)->with('products',$products);
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }

    function new_offer(){
        \DB::transaction(function(){
            $data = request()->validate([
                'name' => 'required',
                'category' => 'required_without:products',
                'products' => 'required_without:category',
                'discount_percentage' => 'required',
                'state' => 'nullable'
            ], [
                'name.required' => 'Debe ingresar el nombre de la oferta',
                'category.required' => 'Debe seleccionar la categoría o los productos a los cuales aplicar la oferta',
                'discount_percentage.required' => 'Debe ingresar el porcentaje de descuento a aplicar'
            ]);
            if (isset($data['state'])){
                $state = 1;
            }else{
                $state = 0;
            }
            Offer::Create([
                'name' => $data['name'],
                'category_id' => $data['category'],
                'discount_percentage' => $data['discount_percentage'],
                'state' => $state
            ]);
            $offer_id = \DB::getPdo()->lastInsertId();
            foreach($data['products'] as $product){
                \App\ProductsOffer::Create([
                    'product_id' => $product,
                    'offer_id' => $offer_id
                ]);
            }
        });
        return redirect(action('OfferController@list_offers'));
    }
    
    function edit_offer(){
        if (isset($_POST['delete_offer'])){
            $data = request('eid');
            $offer = \App\Offer::find($data);
            $offer->delete();
            return redirect(action('OfferController@list_offers'))
                    ->with('success', 'Oferta eliminada con éxito');;
        }else{
            \DB::transaction(function(){
                $data = request()->validate([
                    'eid' => 'required',
                    'ename' => 'required',
                    'ecategory' => 'required_without:eproducts',
                    'eproducts' => 'required_without:ecategory',
                    'ediscount_percentage' => 'required',
                    'estate' => 'nullable'
                ], [
                    'ename.required' => 'Debe ingresar el nombre de la oferta',
                    'ecategory.required' => 'Debe seleccionar la categoría o los productos a los cuales aplicar la oferta',
                    'ediscount_percentage.required' => 'Debe ingresar el porcentaje de descuento a aplicar'
                ]);
                if (isset($data['estate'])){
                    $state = 1;
                }else{
                    $state = 0;
                }
                $offer = \App\Offer::find($data['eid']);
                $offer->name = $data['ename'];
                $offer->category_id = $data['ecategory'];
                $offer->discount_percentage = $data['ediscount_percentage'];
                $offer->state = $state;
                $offer->save();
                
                $products_offers = $offer->products_offers;
                if (isset($data['eproducts'])){
                    $inputlength = sizeof($data['eproducts']);
                }else{
                    $inputlength = 0;
                }
                $count = 0;
                foreach($products_offers as $product_offer){
                    if ($count < $inputlength){ //Actualización
                        $product_offer->product_id = $data['eproducts'][$count];
                        $product_offer->save();
                        $count += 1;
                    }else{ //Eliminación
                        $product_offer->delete();
                    }
                };
                $length = sizeof($products_offers);
                while ($inputlength > $length){ //Inserción
                    \App\ProductsOffer::Create([
                        'offer_id' => $data['eid'],
                        'product_id' => $data['eproducts'][$count]
                    ]);
                    $count += 1;
                    $length += 1;
                };
            });
            return redirect(action('OfferController@list_offers'))
                    ->with('success', 'Oferta modificada con éxito');;
        }
    }
}

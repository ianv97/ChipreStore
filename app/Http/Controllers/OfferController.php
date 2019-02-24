<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\Category;
use App\Product;

class OfferController extends Controller
{
    function index(){
        session_start();
        $offers = Offer::all();
        $categories = Category::all();
        $products = Product::all();

        return view('admin.offers_list')->with('offers',$offers)->with('categories',$categories)->with('products',$products);
    }

    function store(Request $request){
        session_start();
        $offer=new Offer($request->all());
        $offer->min_quantity = (int) $request->min_quantity;

        $offer->save();
        foreach($request->products as $product){
          $offer->products()->attach($product);
        }

        return redirect()->route('admin.offers.index');
    }
}

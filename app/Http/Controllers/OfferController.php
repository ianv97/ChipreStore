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
        $offers = Offer::orderBy('id','ASC');
        $categories = Category::all();
        $products = Product::all();
        return view('admin.offers_list')->with('offers',$offers)->with('categories',$categories)->with('products',$products);
    }

    function create(){
        $categories = Category::orderBy('name','ASC')->pluck('name','id');
        $products = Product::orderBy('name','ASC')->pluck('name','id');
        $request->categories = $categories;
        $request->products = $products;

        return response($request);
    }

    function store(Request $request){
        $product= new Product($request->all());
        $offer=new Offer($request->all());
        return redirect()->route('admin.offers.index');
    }


}

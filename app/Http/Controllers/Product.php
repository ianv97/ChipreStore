<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Product extends Controller
{
    function list_products(){
        session_start();
        if (isset($_SESSION['id'])){
            return view('admin/products_list');
        }else{
            return redirect(action('Session@admin_login'));
        }
    }
    
    function new_product(){
        \DB::transaction(function(){
            $data = request()->validate([
                'name' => 'required',
                'description' => 'nullable',
                'cost_price' => 'nullable',
                'sale_price' => 'required',
                'credit_price' => 'nullable',
                'waists' => 'required',
                'stock_quantity' => 'required',
                'categories' => 'nullable',
                'without_stock_sales' => 'nullable',
                'visible' => 'nullable'
            ], [
                'name.required' => 'Debe ingresar el nombre del producto',
                'sale_price.required' => 'Debe ingresar el precio de venta del producto',
                'waists.required' => 'Debe seleccionar los talles disponibles del producto',
                'stock_quantity.required' => 'Debe ingresar la cantidad en stock del producto'
            ]);
            
            if(isset($data['without_stock_sales'])){
                $without_stock = 1;
            }else{
                $without_stock = 0;
            }
            if(isset($data['visible'])){
                $visible = 1;
            }else{
                $visible = 0;
            }
            \App\Product::Create([
                'name' => $data['name'],
                'description' => $data['description'],
                'cost_price' => $data['cost_price'],
                'sale_price' => $data['sale_price'],
                'credit_price' => $data['credit_price'],
                'without_stock_sales' => $without_stock,
                'visible' => $visible
            ]);
            
            $product_id = \DB::getPdo()->lastInsertId();
            
            foreach($data['categories'] as $category){
                \App\CategoriesProduct::Create([
                    'category_id' => $category,
                    'product_id' => $product_id
                ]);
            }
            
            for ($i=0; $i<count($data['waists']); $i++){
                \App\ProductsWaist::Create([
                    'waist_id' => $data['waists'][$i],
                    'stock_quantity' => $data['stock_quantity'][$i],
                    'product_id' => $product_id
                ]);
            }
            
            $files = request()->file('photos');
            $i = 0;
            foreach ($files as $file) {
                $filename = str_replace(' ', '', $data['name']) . $i . '.' . $file->getClientOriginalExtension();
                \Storage::disk('products_img')->put($filename, \File::get($file));
                \App\ProductPhoto::Create([
                    'name' => $filename,
                    'product_id' => $product_id
                ]);
                $i += 1;
            }
        });
        return redirect(action('Product@list_products'))
                ->with('success', 'Producto registrado con éxito');;
    }
    
    function edit_product(){
        if (isset($_POST['delete_product'])){
            $data = request('eid');
            $product = \App\Product::find($data);
            $product->delete();
            return redirect(action('Product@list_products'))
                    ->with('success', 'Producto eliminado con éxito');;
        }else{
            \DB::transaction(function(){
                $data = request()->validate([
                    'eid' => 'required',
                    'ename' => 'required',
                    'edescription' => 'nullable',
                    'ecost_price' => 'nullable',
                    'esale_price' => 'required',
                    'ecredit_price' => 'nullable',
                    'estock_quantity' => 'required',
                    'ewithout_stock_sales' => 'required',
                    'ecategories' => 'nullable',
                    'ephotos' => 'required'
                ], [
                    'ename.required' => 'Debe ingresar el nombre del producto',
                    'esale_price.required' => 'Debe ingresar el precio de venta del producto',
                    'estock_quantity.required' => 'Debe ingresar la cantidad en stock del producto',
                    'ephotos.required' => 'Debe seleccionar alguna foto del producto'
                ]);
                $product = \App\Category::find($data['eid']);
                $product->name = $data['ename'];
                $product->description = $data['edescription'];
                $product->cost_price = $data['ecost_price'];
                $product->sale_price = $data['esale_price'];
                $product->credit_price = $data['ecredit_price'];
                $product->stock_quantity = $data['estock_quantity'];
                $product->without_stock_sales = $data['ewithout_stock_sales'];
                $product->save();
                
                $photos = $product->photos;
                $inputlength = sizeof($data['ephotos']);
                $count = 0;
                foreach($photos as $photo){
                    if ($count < $inputlength){ //Actualización
                        $photo->name = $data['ephotos'][$count];
                        $photo->save();
                        $count += 1;
                    }else{ //Eliminación
                        $photo->delete();
                    }
                };
                $length = sizeof($photos);
                while ($inputlength > $length){ //Inserción
                    \App\ProductPhoto::Create([
                        'name' => $data['ephotos'][$count],
                        'product_id' => $product_id
                    ]);
                    $count += 1;
                    $length += 1;
                };
                
                $categories_product = $product->categories_product;
                $inputlength = sizeof($data['ecategories']);
                $count = 0;
                foreach($categories_product as $category_product){
                    if ($count < $inputlength){ //Actualización
                        $category_product->category_id = $data['ecategories'][$count];
                        $category_product->save();
                        $count += 1;
                    }else{ //Eliminación
                        $category_product->delete();
                    }
                };
                $length = sizeof($categories_product);
                while ($inputlength > $length){ //Inserción
                    \App\CategoriesProduct::Create([
                        'category_id' => $data['ecategories'][$count],
                        'product_id' => $product_id
                    ]);
                    $count += 1;
                    $length += 1;
                };
                
            });
            return redirect(action('Category@list_categories'))
                    ->with('success', 'Producto modificado con éxito');;
        }
    }
}
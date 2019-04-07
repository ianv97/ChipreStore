<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    function list_products(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/products_list');
        }else{
            return redirect(action('SessionController@admin_login'));
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
                if ($category != "null"){
                    \App\CategoriesProduct::Create([
                        'category_id' => $category,
                        'product_id' => $product_id
                    ]);
                }
            }
            
            for ($i=0; $i<count($data['waists']); $i++){
                if ($data['waists'][$i] != "null"){
                    \App\ProductsWaist::Create([
                        'waist_id' => $data['waists'][$i],
                        'stock_quantity' => $data['stock_quantity'][$i],
                        'product_id' => $product_id
                    ]);
                }else{
                    return redirect(action('ProductController@list_products'))
                    ->withErrors(['repeat_password' => 'Las contraseñas ingresadas no coinciden']);
                }
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
        return redirect(action('ProductController@list_products'))
                ->with('success', 'Producto registrado con éxito');;
    }
    
    function edit_product(){
        if (isset($_POST['delete_product'])){
            $data = request('eid');
            $product = \App\Product::find($data);
            $product->delete();
            return redirect(action('ProductController@list_products'))
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
                    'ewaists' => 'required',
                    'estock_quantity' => 'required',
                    'ecategories' => 'nullable',
                    'ephotos_names' => 'required',
                    'ewithout_stock_sales' => 'nullable',
                    'evisible' => 'nullable'
                ], [
                    'ename.required' => 'Debe ingresar el nombre del producto',
                    'esale_price.required' => 'Debe ingresar el precio de venta del producto',
                    'ewaists.required' => 'Debe seleccionar al menos un talle del producto',
                    'estock_quantity.required' => 'Debe ingresar la cantidad en stock del producto',
                    'ephotos_names.required' => 'Debe seleccionar alguna foto del producto'
                ]);
                
                if(isset($data['ewithout_stock_sales'])){
                    $without_stock = 1;
                }else{
                    $without_stock = 0;
                }
                if(isset($data['evisible'])){
                    $visible = 1;
                }else{
                    $visible = 0;
                }
                
                
                $product = \App\Product::find($data['eid']);
                $product->name = $data['ename'];
                $product->description = $data['edescription'];
                $product->cost_price = $data['ecost_price'];
                $product->sale_price = $data['esale_price'];
                $product->credit_price = $data['ecredit_price'];
                $product->without_stock_sales = $without_stock;
                $product->visible = $visible;
                $product->save();
                
                
                $categories_product = $product->categories_products;
                if (isset($data['ecategories'])){
                    $inputlength = sizeof($data['ecategories']);
                }else{
                    $inputlength = 0;
                }
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
                    if ($data['ecategories'][$count] != "null"){
                        \App\CategoriesProduct::Create([
                            'category_id' => $data['ecategories'][$count],
                            'product_id' => $data['eid']
                        ]);
                    }
                    $count += 1;
                    $length += 1;
                };
                
                
                $product_waists = $product->products_waists;
                $inputlength = sizeof($data['ewaists']);
                $count = 0;
                foreach($product_waists as $product_waist){
                    if ($count < $inputlength){ //Actualización
                        if ($data['ewaists'][$count] != "null"){
                            $product_waist->waist_id = $data['ewaists'][$count];
                            $product_waist->stock_quantity = $data['estock_quantity'][$count];
                            $product_waist->save();
                        }
                        $count += 1;
                    }else{ //Eliminación
                        $product_waist->delete();
                    }
                };
                $length = sizeof($product_waists);
                while ($inputlength > $length){ //Inserción
                    if ($data['ewaists'][$count] != "null"){
                        \App\ProductsWaist::Create([
                            'product_id' => $data['eid'],
                            'waist_id' => $data['ewaists'][$count],
                            'stock_quantity' => $data['estock_quantity'][$count],
                        ]);
                    }
                    $count += 1;
                    $length += 1;
                };
                

                $files = $_FILES['ephotos'];
                $photos = $product->photos;
                $inputlength = sizeof($files["tmp_name"]);
                $count = 0;
                foreach($photos as $photo){
                    if ($count < $inputlength){ //Actualización
                        if ($files['tmp_name'][$count] != ''){ //Si realmente hubo un cambio de foto
                            $explode_name = explode(".", $files['name'][$count]);
                            $filename = str_replace(' ', '', $data['ename']) . $count . '.' . end($explode_name);
                            move_uploaded_file($files['tmp_name'][$count], $_SERVER['DOCUMENT_ROOT'].'/img/product-img/'.$filename);
                            $photo->name = $filename;
                        }else{
                            $photo->name = $data['ephotos_names'][$count];
                            $photo->save();
                        }
                        $count += 1;
                    }else{ //Eliminación
                        $photo->delete();
                    }
                };
                $length = sizeof($photos);
                while ($inputlength > $length){ //Inserción
                    $explode_name = explode(".", $files['name'][$count]);
                    $filename = str_replace(' ', '', $data['ename']) . $count . '.' . end($explode_name);
                    move_uploaded_file($files['tmp_name'][$count], $_SERVER['DOCUMENT_ROOT'].'/img/product-img/'.$filename);
                    \App\ProductPhoto::Create([
                        'name' => $filename,
                        'product_id' => $data['eid']
                    ]);
                    $count += 1;
                    $length += 1;
                };
                
            });
            return redirect(action('ProductController@list_products'))
                    ->with('success', 'Producto modificado con éxito');;
        }
    }
}
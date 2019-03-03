<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller{
    function list_categories(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/categories_list');
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function new_category(){
        $data = request()->validate([
            'name' => 'required',
            'state' => 'nullable'
        ], [
            'name.required' => 'Debe ingresar el nombre de la categoría'
        ]);
        if (isset($data['state'])){
            $state = 1;
        }else{
            $state = 0;
        }
        \App\Category::Create([
            'name' => $data['name'],
            'state' => $state
        ]);
        return redirect(action('CategoryController@list_categories'))
                ->with('success', 'Categoría registrada con éxito');;
    }
    
    function edit_category(){
        if (isset($_POST['delete_category'])){
            $data = request('eid');
            $category = \App\Category::find($data);
            $category->delete();
            return redirect(action('CategoryController@list_categories'))
                    ->with('success', 'Categoría eliminada con éxito');;
        }else{
            $data = request()->validate([
                'eid' => 'required',
                'ename' => 'required',
                'estate' => 'nullable'
            ], [
                'ename.required' => 'Debe ingresar el nombre de la categoría'
            ]);
            if (isset($data['estate'])){
                $state = 1;
            }else{
                $state = 0;
            }
            $category = \App\Category::find($data['eid']);
            $category->name = $data['ename'];
            $category->state = $state;
            $category->save();
            return redirect(action('CategoryController@list_categories'))
                    ->with('success', 'Categoría modificada con éxito');;
        }
    }
}

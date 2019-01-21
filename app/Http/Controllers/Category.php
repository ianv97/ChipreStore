<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Category extends Controller
{
    
    function list_categories(){
        session_start();
        if (isset($_SESSION['id'])){
            return view('admin/categories_list');
        }else{
            return redirect(action('Session@admin_login'));
        }
    }
    
    function new_category(){
        $data = request()->validate([
            'name' => 'required'
        ], [
            'name.required' => 'Debe ingresar el nombre de la categoría'
        ]);
        \App\Category::Create([
            'name' => $data['name']
        ]);
        return redirect(action('Category@list_categories'))
                ->with('success', 'Categoría registrada con éxito');;
    }
    
    function edit_category(){
        if (isset($_POST['delete_category'])){
            $data = request('eid');
            $category = \App\Category::find($data);
            $category->delete();
            return redirect(action('Category@list_categories'))
                    ->with('success', 'Categoría eliminada con éxito');;
        }else{
            $data = request()->validate([
                'eid' => 'required',
                'ename' => 'required'
            ], [
                'ename.required' => 'Debe ingresar el nombre de la categoría',
            ]);
            $category = \App\Category::find($data['eid']);
            $category->name = $data['ename'];
            $category->save();
            return redirect(action('Category@listcategories'))
                    ->with('success', 'Categoría modificada con éxito');;
        }
    }
    
    
//    public function index(Request $request){
//        $categories = Category::search($request->name)->orderBy('name','ASC')->paginate(15);
//        return view('admin.pages.tables.categories')->with('categories',$categories);
//    }
//
//    public function create(){
//        return view('admin.pages.createCategory');
//    }
//
//    public function store(CategoryRequest $request){
//        $category= new Category($request->all());
//        $category->save();
//
//        //Flash::success("Se ha registrado correctamente" . $category->name);
//
//        return redirect()->route('categories.index');
//    }
//
//    public function destroy($id){
//        $category = Category::find($id);
//        $category->delete();
//
//        //Flash::error('Usuario'." ". $category->name ." ".'Eliminado Exitosamente');
//        return redirect()->route('categories.index');
//    }
//
//    public function edit($id){
//        $category = Category::find($id);
//        return view('admin.pages.editCategory')->with('category',$category);
//    }
//
//    public function update(Request $request, $id){
//        $category = Category::find($id);
//        $category->fill($request->all());
//        $category->save();
//
//        //Flash::success("Se ha modificado con exito");
//        return redirect()->route('categories.index');
//    }

}

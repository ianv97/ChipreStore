<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;


class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::search($request->name)->orderBy('name','ASC')->paginate(15);
        return view('admin.pages.tables.categories')->with('categories',$categories);
    }

    public function create(){
        return view('admin.pages.createCategory');
    }

    public function store(CategoryRequest $request){
        $category= new Category($request->all());
        $category->save();

        //Flash::success("Se ha registrado correctamente" . $category->name);

        return redirect()->route('categories.index');
    }

    public function destroy($id){
        $category = Category::find($id);
        $category->delete();

        //Flash::error('Usuario'." ". $category->name ." ".'Eliminado Exitosamente');
        return redirect()->route('categories.index');
    }

    public function edit($id){
        $category = Category::find($id);
        return view('admin.pages.editCategory')->with('category',$category);
    }

    public function update(Request $request, $id){
        $category = Category::find($id);
        $category->fill($request->all());
        $category->save();

        //Flash::success("Se ha modificado con exito");
        return redirect()->route('categories.index');
    }

}

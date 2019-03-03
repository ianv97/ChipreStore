<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaistController extends Controller{
    
    function list_waists(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/waists_list');
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function new_waist(){
        $data = request()->validate([
            'name' => 'required'
        ], [
            'name.required' => 'Debe ingresar el nombre del talle'
        ]);
        \App\Waist::Create([
            'name' => $data['name']
        ]);
        return redirect(action('WaistController@list_waists'))
                ->with('success', 'Talle registrado con éxito');;
    }
    
    function edit_waist(){
        if (isset($_POST['delete_waist'])){
            $data = request('eid');
            $waist = \App\Waist::find($data);
            $waist->delete();
            return redirect(action('WaistController@list_waists'))
                    ->with('success', 'Talle eliminado con éxito');;
        }else{
            $data = request()->validate([
                'eid' => 'required',
                'ename' => 'required'
            ], [
                'ename.required' => 'Debe ingresar el nombre del talle'
            ]);
            $waist = \App\Waist::find($data['eid']);
            $waist->name = $data['ename'];
            $waist->save();
            return redirect(action('WaistController@list_waists'))
                    ->with('success', 'Talle modificado con éxito');;
        }
    }
}
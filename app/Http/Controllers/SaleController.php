<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller{
    
    function list_sales(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/sales_list')->with('sales', \App\Sale::all());
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function edit_sale(){
        if (isset($_POST['delete_sale'])){
            $data = request('eid');
            $sale = \App\Sale::find($data);
            $sale->delete();
            return redirect(action('SaleController@list_sales'))
                    ->with('success', 'Venta eliminada con éxito');;
        }else{
            $data = request()->validate([
                'eid' => 'required',
                'state' => 'required'
            ], [
                'state.required' => 'Debe seleccionar el estado del pedido'
            ]);

            $sale = \App\Sale::find($data['eid']);
            $sale->state = $data['state'];
            $sale->save();

            return redirect(action('SaleController@list_sales'))
                    ->with('success', 'Venta modificada con éxito');
        }
    }
}

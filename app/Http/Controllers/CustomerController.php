<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    function signup(){
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'repeat_password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'province' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ], [
            'email.required' => 'Debe ingresar su email',
            'email.email' => 'El email ingresado no es válido',
            'password.required' => 'Debe ingresar una contraseña',
            'repeat_password.required' => 'Debe repetir la contraseña',
            'first_name.required' => 'Debe ingresar el nombre',
            'last_name.required' => 'Debe ingresar el apellido',
            'province.required' => 'Debe seleccionar una provincia',
            'city.required' => 'Debe seleccionar una ciudad',
            'phone.required' => 'Debe ingresar un número de teléfono',
            'address.required' => 'Debe ingresar una dirección'
        ]);
        if ($data['password'] == $data['repeat_password']){
            try{
                $customer= \App\Customer::Create([
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'name' => $data['last_name'] . ', ' . $data['first_name'],
                    'city_id' => $data['city'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                ]);
                $customer->setRememberToken(\Illuminate\Support\Str::random(60));
                $customer->save();
            }catch (\Illuminate\Database\QueryException $e){
                if($e->errorInfo[1] == 1062){
                    $error = 'Ya existe un usuario registrado con el email ingresado';
                    return back()->withErrors(['db' => $error]);
                }
            };
            return redirect(action('SessionController@login'))
                    ->with('success', 'Usuario registrado con éxito');
        }else{
            return redirect(action('SessionController@login'))
                    ->withErrors(['repeat_password' => 'Las contraseñas ingresadas no coinciden']);
        }
    }
    
    function list_purchases(){
        if (!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['role'])){
            if ($_SESSION['role'] = 'Cliente'){
                return view('purchases_list')->with('purchases', \App\Sale::where('customer_id', $_SESSION['id'])->get());
            }else{
                return redirect(action('SessionController@login'));
            }
        }else{
            return redirect(action('SessionController@login'));
        }
    }
    
    function index(){
        $customers = Customer::orderBy('id','ASC')->paginate(25);

        return view('admin.customers_list')->with('customers',$customers); //agregar nombre de la vista de ADMINISTRACION
    }

    function signupOtro(Request $request){
        $customer= new Customer($request->all());
        $customer->password = bcrypt($request->password);
        $customer->save();

        return redirect()->route('customers.index');
    }
}

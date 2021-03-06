<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Customer;

class SessionController extends Controller
{
    function login(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Cliente'){
            return redirect(action('ShopController@index'));
        }else{
            return view('login');
        }
    }
    
    function admin_login(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return redirect(action('UserController@index'));
        }else{
            return view('admin/login');
        }
    }
    
    function authenticate(){
        $data = request()->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Debe ingresar su email.',
            'password.required' => 'Debe ingresar la contraseña.',
        ]);
        $customer = Customer::where('email', $data['email'])->first();
        if (isset($customer) and password_verify($data['password'], $customer->password)){
            session_start();
            $_SESSION['id'] = $customer->id;
            $_SESSION['name'] = $customer->name;
            $_SESSION['email'] = $data['email'];
            $_SESSION['role'] = 'Cliente';
            if (isset($_COOKIE["product"])){
                return redirect(action('ShopController@cart'));
            }else{
                return redirect(action('ShopController@index'));
            }
        }else{
            return redirect(action('SessionController@login'))->withErrors(['auth' => 'El email y/o contraseña ingresados son incorrectos.']);
        }
    }
    
    function admin_authenticate(){
        $data = request()->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Debe ingresar su email.',
            'password.required' => 'Debe ingresar la contraseña.',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (isset($user) and password_verify($data['password'], $user->password)){
            session_start();
            $_SESSION['id'] = $user->id;
            $_SESSION['name'] = $user->name;
            $_SESSION['email'] = $data['email'];
            if (isset($user->photo)){
                $_SESSION['photo'] = $user->photo;
            }else{
                $_SESSION['photo'] = 'user.jpg';
            }
            $_SESSION['role'] = $user->role;
            return redirect(action('UserController@index'));
        }else{
            return redirect(action('SessionController@admin_login'))->withErrors(['auth' => 'El email y/o contraseña ingresados son incorrectos.']);
        }
    }
    
    function logout(){
        session_start();
        session_destroy();
        return redirect(action('ShopController@index'));
    }
}
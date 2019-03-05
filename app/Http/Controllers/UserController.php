<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function index(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/index');
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function edit_password(){
        session_start();
        if (isset($_SESSION['role']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/change_password');
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function change_password(){
        session_start();
        $data = request()->validate([
            'oldpassword' => 'required',
            'newpassword1' => 'required',
            'newpassword2' => 'required',
        ], [
            'oldpassword.required' => 'Debe ingresar su contraseña actual.',
            'newpassword1.required' => 'Debe ingresar la nueva contraseña.',
            'newpassword2.required' => 'Debe repetir la nueva contraseña.',
        ]);
        if ($data['newpassword1'] == $data['newpassword2']){
            $user = \App\User::find($_SESSION['id']);
            if (password_verify($data['oldpassword'], $user->password)){
                $user->password = bcrypt($data['newpassword1']);
                $user->save();
                return redirect(action('UserController@edit_password'))->with('success', 'Contraseña modificada con éxito.');
            }else{
                return redirect(action('UserController@edit_password'))->withErrors(['oldpassword' => 'La contraseña ingresada es incorrecta']);;
            }
        }else{
            return redirect(action('UserController@edit_password'))->withErrors(['newpassword2' => 'Las contraseñas nuevas no coinciden']);
        }
    }
    
    function change_photo(){
        session_start();
        $file = request()->file('userphotofile');
        $filename = substr($_SESSION['email'], 0, strpos($_SESSION['email'], '@')) . '.' . $file->getClientOriginalExtension();
        \Storage::disk('users_img')->put($filename, \File::get($file));
        $_SESSION['photo'] = $filename;
        $user = \App\User::find($_SESSION['id']);
        $user->photo = $filename;
        $user->save();
        return redirect(action('UserController@index'));
    }
    
    function list_users(){
        session_start();
        if (isset($_SESSION['id']) and $_SESSION['role'] == 'Administrador'){
            return view('admin/users_list');
        }else{
            return redirect(action('SessionController@admin_login'));
        }
    }
    
    function new_user(){
        $data = request()->validate([
            'role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password1' => 'required',
            'password2' => 'required'
        ], [
            'role.required' => 'Debe seleccionar el rol del usuario',
            'email.required' => 'Debe ingresar el email',
            'email.email' => 'El email ingresado no es válido',
            'first_name.required' => 'Debe ingresar el nombre',
            'last_name.required' => 'Debe ingresar el apellido',
            'password1.required' => 'Debe ingresar una contraseña',
            'password2.required' => 'Debe repetir la contraseña'
        ]);
        if ($data['password1'] == $data['password2']){
            $user = \App\User::Create([
                'role' => $data['role'],
                'email' => $data['email'],
                'name' => $data['last_name'] . ', ' . $data['first_name'],
                'password' => bcrypt($data['password1'])
            ]);
            $user->setRememberToken(\Illuminate\Support\Str::random(60));
            $user->save();
            return redirect(action('UserController@list_users'))
                    ->with('success', 'Usuario registrado con éxito');;
        }else{
            return redirect(action('UserController@list_users'))
                    ->withErrors(['password2' => 'Las contraseñas ingresadas no coinciden']);;
        }
    }
    
    function edit_user(){
        if (isset($_POST['delete_user'])){
            $data = request('eid');
            $user = \App\User::find($data);
            $user->delete();
            return redirect(action('UserController@list_users'))
                    ->with('success', 'Usuario eliminado con éxito');;
        }else{
            $data = request()->validate([
                'eid' => 'required',
                'erole' => 'required',
                'eemail' => 'required',
                'efirst_name' => 'required',
                'elast_name' => 'required'
            ], [
                'erole.required' => 'Debe seleccionar el rol del empleado',
                'eemail.required' => 'Debe ingresar el email',
                'eemail.email' => 'El email ingresado no es válido',
                'efirst_name.required' => 'Debe ingresar el nombre',
                'elast_name.required' => 'Debe ingresar el apellido'
            ]);
            $user = \App\User::find($data['eid']);
            $user->role = $data['erole'];
            $user->email = $data['eemail'];
            $user->name = $data['elast_name'] . ', ' . $data['efirst_name'];
            $user->save();
            return redirect(action('UserController@list_users'))
                    ->with('success', 'Usuario actualizado con éxito');;
        }
    }
}
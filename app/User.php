<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Notifications\ResetPasswordNotification;

class User extends Authenticatable{
    use Notifiable;
    
    protected $fillable = [
        'email', 'password', 'name', 'dni', 'photo', 'salary', 'comission', 'address', 'role'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function getLastNameAttribute(){
        return substr($this->name, 0, strpos($this->name, ','));
    }
    
    public function getFirstNameAttribute(){
        return substr($this->name, strpos($this->name, ',') + 2);
    }
    
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

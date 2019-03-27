<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Notifications\CustomerResetPasswordNotification;

class Customer extends Authenticatable{
    use Notifiable;
    
    protected $fillable = [
        'email', 'password', 'name', 'city_id', 'address', 'phone'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function sales(){
        return $this->hasMany(Sale::class);
    }
    
    public function city(){
        return $this->belongsTo(City::class);
    }
    
    public function getLastNameAttribute(){
        return substr($this->name, 0, strpos($this->name, ','));
    }
    
    public function getFirstNameAttribute(){
        return substr($this->name, strpos($this->name, ',') + 2);
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }
}

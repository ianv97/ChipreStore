<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'email', 'password', 'name', 'city_id', 'address', 'phone'
    ];
    
    public function sales(){
        return $this->hasMany(Sale::class);
    }
}

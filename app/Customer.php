<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'email', 'password', 'name', 'dni', 'address'
    ];
    
    public function sales(){
        return $this->hasMany(Sale::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waist extends Model
{
    protected $fillable = [
        'name'
    ];
    
    public function products_waists(){
        return $this->hasMany(ProductsWaist::class);
    }
}
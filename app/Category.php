<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'state'
    ];
    
    public function categories_products(){
        return $this->hasMany(CategoriesProduct::class);
    }

    public function offers(){
        return $this->hasMany(Offer::class);
    }
}
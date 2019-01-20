<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'state'
    ];
    
    public function products(){
        return $this->hasMany(Product::class);
    }

    public function scopeSearch($query, $name){
        return $query->where('name', 'LIKE', "%$name%");
    }
}
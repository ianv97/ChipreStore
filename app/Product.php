<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'cost_price', 'sale_price', 'credit_price', 'without_stock_sales', 'visible'
    ];
    
    public function categories_products() {
        return $this->hasMany(CategoriesProduct::class);
    }
    
    public function offers(){
        return $this->belongsToMany('App\Offer');
    }
    
    public function sale_lines(){
        return $this->hasMany(SaleLine::class);
    }
    
    public function photos(){
        return $this->hasMany(ProductPhoto::class);
    }
    
    public function products_waists(){
        return $this->hasMany(ProductsWaist::class);
    }
}
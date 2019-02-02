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
    
    public function products_offers(){
        return $this->hasMany(ProductsOffer::class);
    }
    
    public function sale_lines(){
        return $this->hasMany(SaleLine::class);
    }
    
    public function photos(){
        return $this->hasMany(ProductPhoto::class);
    }
    
    public function products_waists(){
        return $this->hasMany(ProductsWaists::class);
    }
}
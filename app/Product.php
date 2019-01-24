<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'cost_price', 'sale_price', 'credit_price', 'stock_quantity', 'without_stock_sales'
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
}
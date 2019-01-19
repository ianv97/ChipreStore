<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'photo', 'cost_price', 'sale_price', 'credit_price', 'stock_quantity', 'without_stock_sales', 'category_id'
    ];
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function products_offers(){
        return $this->hasMany(ProductsOffer::class);
    }
    
    public function sale_lines(){
        return $this->hasMany(SaleLine::class);
    }
}
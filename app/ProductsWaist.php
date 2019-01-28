<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsWaist extends Model
{
    protected $fillable = [
        'stock_quantity', 'product_id', 'waist_id'
    ];
    
    public function product(){
        return $this->belongsTo(Product::class);
    }
    
    public function waist(){
        return $this->belongsTo(Waist::class);
    }
}
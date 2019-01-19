<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsOffer extends Model
{
    protected $fillable = [
        'product_id', 'offer_id'
    ];
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function offer() {
        return $this->belongsTo(Offer::class);
    }
}
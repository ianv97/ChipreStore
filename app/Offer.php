<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name', 'discount_percentage', 'state', 'category_id'
    ];
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function products_offers(){
        return $this->hasMany(ProductsOffer::class);
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleLine extends Model
{
    protected $fillable = [
        'quantity', 'subtotal', 'product_id', 'sale_id'
    ];
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function sale() {
        return $this->belongsTo(Sale::class);
    }
}

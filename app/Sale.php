<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'date', 'total'
    ];
    
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    
    public function sale_lines(){
        return $this->hasMany(SaleLine::class);
    }
    
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}

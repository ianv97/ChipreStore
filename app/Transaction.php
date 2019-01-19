<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'date', 'state', 'sale_id'
    ];
    
    public function sale() {
        return $this->belongsTo(Sale::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'title', 'photo', 'description', 'publication_place'
    ];
}

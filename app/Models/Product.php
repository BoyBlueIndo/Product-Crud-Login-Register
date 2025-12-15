<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'genres_id',
        'description',
        'price',
        'stock',
        'image',
    ];

    public function genre() 
    {
        return $this->belongsTo(Genre::class, 'genres_id');
    }
}

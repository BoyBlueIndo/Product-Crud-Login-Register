<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'payment_method',
    ];

    public function items() {
        return $this->hasMany(Transaction_item::class, 'transaction_id');
    }

    public function user() {
        return $this->belongsto(User::class);
    }
}
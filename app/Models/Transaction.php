<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'payment_method',
        'cash_paid',
        'change_amount',
    ];

    public function items() {
        return $this->hasMany(Transaction_item::class, 'transaction_id');
    }

    public function user() {
        return $this->belongsto(User::class);
    }
}
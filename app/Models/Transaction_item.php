<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_item extends Model
{
    protected $fillable = [
        'transaction_id', 
        'product_id',
        'quantity'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(product::class, 'product_id');
    }
}

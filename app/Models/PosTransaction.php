<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    protected $fillable = ['user_id', 'items', 'total_price', 'payment_method'];

    protected $casts = ['items' => 'array', 'total_price' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
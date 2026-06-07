<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTier extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'discount_percent' => 'integer',
        'price_level' => 'integer',
    ];

    public function assignments()
    {
        return $this->hasMany(CustomerTierAssignment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'customer_tier_assignments');
    }
}
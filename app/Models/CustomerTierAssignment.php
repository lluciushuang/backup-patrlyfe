<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTierAssignment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(CustomerTier::class, 'customer_tier_id');
    }

    public function isActive(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }
}
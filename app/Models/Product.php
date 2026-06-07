<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'base_price' => 'decimal:2',
        'current_stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPriceTiers()
    {
        return $this->hasMany(ProductPriceTier::class);
    }

    public function getEffectivePriceAttribute()
    {
        $price = $this->prices->where('price_level', 1)->first();
        return $price ? $price->price : ($this->base_price ?? 0);
    }

    public function getTierPriceAttribute()
    {
        return function ($tierDiscountPercent) {
            $basePrice = $this->getEffectivePriceAttribute();
            return max(0, $basePrice - ($basePrice * $tierDiscountPercent / 100));
        };
    }
}
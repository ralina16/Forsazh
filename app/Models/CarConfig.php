<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarConfig extends Model
{
    protected $fillable = [
        'car_key', 'name', 'base_price', 'variant', 'description', 'year', 'config_data'
    ];

    protected $casts = [
        'config_data' => 'array',
        'base_price' => 'float',
        'year' => 'integer',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(CarMedia::class);
    }

    public function mainImage()
    {
        return $this->media()->where('type', 'main_image')->first();
    }

    public function interiorImages()
    {
        return $this->media()->where('type', 'interior_image')->orderBy('sort_order');
    }

    public function colorImages()
    {
        return $this->media()->where('type', 'color_image');
    }

    public function threeDModels()
    {
        return $this->media()->where('type', 'model_3d');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price, 0, '.', ' ') . ' ₽';
    }
}
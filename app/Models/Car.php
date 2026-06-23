<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $table = 'cars';
    
    protected $fillable = [
        'brand', 'model', 'photo', 'catalog_photo', 'drive', 'engine', 'fuel',
        'mileage', 'condition', 'owners', 'transmissions', 'trunk', 'gearbox',
        'body', 'price', 'description',
    ];
    
    protected $casts = [
        'engine' => 'float',
        'mileage' => 'integer',
        'owners' => 'integer',
        'transmissions' => 'integer',
        'trunk' => 'integer',
        'price' => 'integer',
    ];

    
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, '.', ' ') . ' ₽';
    }
    
    public function getIsNewAttribute(): bool
    {
        return mb_strtolower($this->condition ?? '') === 'новая';
    }
    
    public function getBadgeTextAttribute(): string
    {
        return $this->is_new ? 'НОВЫЙ' : 'С ПРОБЕГОМ';
    }
    
    public function getBadgeClassAttribute(): string
    {
        return $this->is_new ? 'badge-new' : 'badge-used';
    }
    
    public function getDisplayModelAttribute(): string
    {
        $model = htmlspecialchars($this->model);
        return mb_strlen($model) > 20 ? mb_substr($model, 0, 20) . '...' : $model;
    }
    
    public function getCatalogPhotoUrlAttribute(): string
    {
        if ($this->catalog_photo) {
            return asset('storage/' . $this->catalog_photo);
        }
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('assets/images/offer/1.png');
    }
    
    public function getBrandNameAttribute(): string
    {
        return $this->brand ?? explode(' ', $this->model)[0] ?? '';
    }
    
    public function getBodyTypeLowerAttribute(): string
    {
        return strtolower($this->body ?? '');
    }
    
    public function getBrandLowerAttribute(): string
    {
        return strtolower($this->brand_name);
    }
    
    // === СВЯЗИ ===
    
    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class);
    }

    public function favoritedByUsers()
{
    return $this->hasMany(UserFavorite::class);
}
}
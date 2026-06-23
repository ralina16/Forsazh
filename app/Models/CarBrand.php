<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $table = 'car_brands';
    
    protected $fillable = ['name', 'sort_order', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    
    public function getNameLowerAttribute(): string
    {
        return strtolower($this->name);
    }
    
    public function getIconUrlAttribute(): string
    {
        if ($this->icon) {
            return asset('storage/' . ltrim($this->icon, '/'));
        }
        return asset('assets/images/marks/1.png');
    }
    
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
    
    
    public function cars()
    {
        return $this->hasMany(Car::class, 'brand', 'name');
    }
}
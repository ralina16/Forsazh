<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    protected $table = 'car_images';
    
    protected $fillable = ['car_id', 'path', 'is_main'];
    
    protected $casts = ['is_main' => 'boolean'];
    
    public $timestamps = false;
    
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
    
    public function getUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->path, '/'));
    }
}
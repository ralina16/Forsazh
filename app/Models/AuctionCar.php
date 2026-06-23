<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuctionCar extends Model
{
    protected $table = 'auction_cars';

    protected $fillable = [
        'model', 'photo', 'additional_photos', 'drive', 'engine', 'fuel',
        'mileage', 'condition', 'owners', 'transmissions', 'trunk', 'gearbox',
        'body', 'price', 'description'
    ];

    protected $casts = [
        'additional_photos' => 'array',
        'price' => 'integer',
        'owners' => 'integer',
        'transmissions' => 'integer',
    ];

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'car_id');
    }

    public function getAdditionalPhotosAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, '.', ' ') . ' ₽';
    }

    public function getShortDescriptionAttribute($length = 50): string
    {
        return mb_strlen($this->description) > $length
            ? mb_substr($this->description, 0, $length) . '...'
            : $this->description;
    }
}
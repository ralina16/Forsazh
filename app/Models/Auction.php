<?php

namespace App\Models;
use Illuminate\Support\Facades\Log; 

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Auction extends Model
{
    protected $fillable = [
        'car_id',
        'start_date',
        'end_date',
        'starting_price',
        'reserve_price',
        'current_bid',    
        'bid_count',    
        'winner_name',
        'winner_email',
        'final_price',
        'winner_notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'current_bid' => 'decimal:2', 
        'bid_count' => 'integer',      
    ];

    public function getStatusAttribute()
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        
        if ($now->lt($startDate)) {
            return 'upcoming';
        } elseif ($now->gte($startDate) && $now->lte($endDate)) {
            return 'active';
        } else {
            return 'ended';
        }
    }

    public function isActive()
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        
        return $now->gte($startDate) && $now->lte($endDate);
    }
    
    public function isUpcoming()
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        
        return $now->lt($startDate);
    }
    
    public function isEnded()
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($this->end_date);
        
        return $now->gt($endDate);
    }
    
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'upcoming' => 'Скоро начнется',
            'active' => 'Активен',
            'ended' => 'Завершен',
            default => 'Неизвестно',
        };
    }
    
    public function getStatusClassAttribute()
    {
        return match($this->status) {
            'upcoming' => 'status-upcoming',
            'active' => 'status-active',
            'ended' => 'status-ended',
            default => '',
        };
    }
    
    public function getTimeLeftAttribute()
    {
        $now = Carbon::now();
        
        if ($this->isUpcoming()) {
            return $now->diffInSeconds(Carbon::parse($this->start_date));
        } elseif ($this->isActive()) {
            return $now->diffInSeconds(Carbon::parse($this->end_date));
        }
        
        return 0;
    }
    
    public function getHumanTimeLeftAttribute()
    {
        $seconds = $this->time_left;
        
        if ($seconds <= 0) {
            return $this->isActive() ? 'Завершен' : ($this->isUpcoming() ? 'Скоро' : 'Завершен');
        }
        
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($days > 0) {
            return "{$days} дн. {$hours} ч.";
        } elseif ($hours > 0) {
            return "{$hours} ч. {$minutes} мин.";
        } else {
            return "{$minutes} мин.";
        }
    }
    
    public function car()
    {
        return $this->belongsTo(AuctionCar::class, 'car_id');
    }
    
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    
    public function getCurrentMaxBidAttribute()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }
    
    public function getCurrentPriceAttribute()
    {
        $maxBid = $this->getCurrentMaxBidAttribute();
        return $maxBid ? $maxBid->amount : $this->starting_price;
    }
}
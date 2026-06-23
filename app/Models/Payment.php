<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'status',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'bids';

    protected $fillable = [
        'user_id', 
        'auction_id', 
        'amount',
        'is_winner'
    ];

    protected $casts = [
        'amount' => 'integer',
        'is_winner' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
}
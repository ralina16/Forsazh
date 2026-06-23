<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_name', 'rating', 'comment', 'status'];
    
    protected $casts = [
        'rating' => 'integer',
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
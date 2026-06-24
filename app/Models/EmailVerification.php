<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'verification_enabled',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verification_enabled' => 'boolean',
    ];
}
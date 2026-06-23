<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    protected $table = 'requests';
    
    protected $fillable = [
        'request_type',
        'name', 
        'phone',
        'email',
        'agree',   
        'status',
    ];
    
    protected $casts = [
        'agree' => 'boolean',  
    ];
}
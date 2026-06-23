<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRequest extends Model
{
    protected $fillable = [
        'fio', 'phone', 'insurance_type', 'car_price', 'car_age',
        'estimated_premium', 'monthly_payment', 'risk_level'
    ];
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditRequest extends Model
{
    protected $fillable = [
        'fio', 'phone', 'car_type', 'credit_amount', 'interest_rate',
        'loan_term', 'monthly_payment', 'insurance_kasko', 'insurance_as_z',
        'early_repayment', 'notes', 'consent'
    ];

    protected $casts = [
        'insurance_kasko' => 'boolean',
        'insurance_as_z' => 'boolean',
        'early_repayment' => 'boolean',
        'consent' => 'boolean',
    ];
}
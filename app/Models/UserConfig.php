<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConfig extends Model
{
    protected $fillable = [
        'user_id', 'car_config_id', 'config_name', 'total_price',
        'selected_engine', 'selected_color', 'selected_interior'
    ];

    protected $casts = [
        'total_price' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carConfig(): BelongsTo
    {
        return $this->belongsTo(CarConfig::class);
    }
}
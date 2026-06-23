<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarMedia extends Model
{
    protected $fillable = [
        'car_config_id',
        'type',
        'file_path',
        'interior_key',
        'color_key',      
        'sort_order',
        'title'
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function carConfig(): BelongsTo
    {
        return $this->belongsTo(CarConfig::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getBase64Attribute()
    {
        if ($this->type === 'main_image') {
            $content = Storage::disk('public')->get($this->file_path);
            return base64_encode($content);
        }
        return null;
    }
}
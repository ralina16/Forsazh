<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    
    protected $fillable = [
        'user_id',
        'car_config_id',
        'message_text',
        'message_type',
        'message_format',
        'is_read',
        'chat_type',  
        'created_at'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];
    
    public $timestamps = false;
    
    public function chatUser()
    {
        return $this->belongsTo(ChatUser::class, 'user_id', 'user_id');
    }
    
    public function isSent(): bool
    {
        return $this->message_type === 'sent';
    }
    
    public function isReceived(): bool
    {
        return $this->message_type === 'received';
    }
    
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('H:i') : '';
    }
}
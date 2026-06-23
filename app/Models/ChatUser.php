<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    protected $table = 'chat_users';
    
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'last_activity',
        'message_count'
    ];
    
    protected $casts = [
        'last_activity' => 'datetime',
        'created_at' => 'datetime',
        'message_count' => 'integer',
    ];
    
    public $timestamps = false;
    
    public function touchLastActivity(): void
    {
        $this->last_activity = now();
        $this->save();
    }
    
    public function messages()
{
    return $this->hasMany(Message::class, 'user_id', 'user_id');
}
    public function incrementMessageCount(): void
    {
        $this->increment('message_count');
        $this->touchLastActivity();
    }
}
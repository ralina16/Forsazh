<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    protected $table = 'user_favorites';

    public $timestamps = false;

    protected $fillable = ['user_id', 'car_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
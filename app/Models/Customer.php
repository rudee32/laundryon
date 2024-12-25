<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'telegram_username',
        'telegram_chat_id'
    ];

    protected $casts = [
        'telegram_chat_id' => 'string'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
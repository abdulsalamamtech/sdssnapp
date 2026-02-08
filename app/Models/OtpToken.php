<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'email',
        'type',
        'token',
        'expires'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

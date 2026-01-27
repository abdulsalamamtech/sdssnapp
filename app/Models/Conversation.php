<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'sent_by',
        'sent_to',
        'subject',
        'message',
        'reason',
    ];

    protected $casts = [
        'sent_to' => 'array',
    ];


    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    // get all the users by ids
    public function sentTo()
    {
        $userIds = $this->sent_to;
        return User::whereId($userIds)->get();
    }
}

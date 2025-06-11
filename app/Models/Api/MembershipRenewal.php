<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipRenewal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'membership_id',
        'previously_issued_on',
        'previously_expires_on',
        'issued_on',
        'expires_on',
        'renewal_date',
        'status', // pending, paid
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership(){
        return $this->belongsTo(Membership::class);
    }

}

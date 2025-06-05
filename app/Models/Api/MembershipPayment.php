<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'membership_id',
        'payment_type', // [new, renewal]
        'payment_method',
        'amount',
        'reference',
        'status',
        'data',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function membership(){
        return $this->belongsTo(Membership::class);
    }


}

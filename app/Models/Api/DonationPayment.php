<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DonationPayment extends Model
{
    protected $fillable = [
        'user_id',
        'donation_id',
        'payment_type', // [donation]
        'payment_method',
        'amount',
        'reference',
        'status',
        'data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function donation()
    {
        return parent::belongsTo(Donation::class);
    }
}

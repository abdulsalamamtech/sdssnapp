<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'amount',
        'reason_for_donation',
        'note',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function user()
    {
        return parent::belongsTo(User::class);
    }

    public function donationPayments()
    {
        return parent::hasMany(DonationPayment::class);
    }
}

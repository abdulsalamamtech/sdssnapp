<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $table = 'memberships';

    protected $fillable = [
        'user_id',
        'full_name',
        'certification_request_id',
        'issued_on',
        'expires_on',
        'serial_no',
        'qr_code',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    // certification request
    public function certificationRequest()
    {
        return $this->hasOne(CertificationRequest::class);
    }
}

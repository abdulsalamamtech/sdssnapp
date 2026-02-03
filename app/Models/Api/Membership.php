<?php

namespace App\Models\Api;

use App\Models\Certification;
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
        'membership_code',
        'qr_code',
        'status', // pending, paid
        // $table->string('certificate_status')->nullable()->default('processing'); // processing generated, expired etc
        'certificate_status',
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
        return $this->belongsTo(CertificationRequest::class);
    }

    // belongs to certification through certification_request
    // public function certification()
    // {
    //     // This is a one-to-one relationship through another model
    //     // return $this->belongsToThrough(Certification::class, CertificationRequest::class, 'id', 'id', 'certification_request_id', 'certification_id');
    //     // return $this->belongsToOneThrough(Certification::class, CertificationRequest::class);
    //     // return $this->certificationRequest()->certification;
    //     // return $this->certificationRequest()->belongsTo(Certification::class, 'certification_id');
    //     // return $this->belongsTo(CertificationRequest::class)->belongsTo(Certification::class, 'certification_id');
    //     return app($this->certificationRequest())->certification;

    // }

    // membershipPayment
    public function membershipPayments()
    {
        return $this->hasMany(MembershipPayment::class);
    }

    // belongs to many through certification_request to certification
    public function certifications()
    {
        return $this->hasManyThrough(
            Certification::class,
            CertificationRequest::class,
            'id', // Foreign key on CertificationRequest table
            'id', // Foreign key on Certification table
            'certification_request_id', // Local key on Membership table
            'certification_id' // Local key on CertificationRequest table
        );
    }

}

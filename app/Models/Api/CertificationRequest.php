<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'certification_id',
        'full_name',
        'user_signature_id',
        'reason_for_certification',
        'management_note',
        'credential_id',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'certification_id' => 'integer',
        'user_signature_id' => 'integer',
        'credential_id' => 'integer',
        'status' => 'string',
    ];
    /**
     * Get the user that owns the certification request.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    /**
     * Get the certification associated with the request.
     */
    public function certification()
    {
        return $this->belongsTo('App\Models\Certification', 'certification_id');
    }
    /**
     * Get the user signature associated with the request.
     */
    public function userSignature()
    {
        return $this->belongsTo('App\Models\Assets', 'user_signature_id');
    }
    /**
     * Get the credential associated with the request.
     */
    public function credential()
    {
        return $this->belongsTo('App\Models\Assets', 'credential_id');
    }
    /**
     * Get the membership.
     */
    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    
    /**
     * Get the user who created the certification request.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}

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
}

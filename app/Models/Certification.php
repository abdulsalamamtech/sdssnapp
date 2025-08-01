<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'management_signature_id',
        'secretary_signature_id',
        'organization_name', // Spatial and Data Science Society of Nigeria
        'title', // Certified Spatial and Data Scientist
        'type', // Professional Certification
        'for', // individual or organization
        'abbreviation_code', // GRT, CER
        'duration',
        'duration_unit',
        'amount',
        'benefits',
        'requirements',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'management_signature_id' => 'integer',
        // 'amount' => 'decimal:2', // string or integer based on your needs
        'duration' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $attributes = [
        'organization_name' => 'Spatial and Data Science Society of Nigeria',
        'title' => 'Certified Spatial and Data Scientist',
        'type' => 'Professional Certification',
        'duration' => 2,
        'duration_unit' => 'years',
        'benefits' => 'Access to exclusive resources, networking opportunities, and professional development workshops.',
    ];

    public function managementSignature()
    {
        return $this->belongsTo(ManagementSignature::class, 'management_signature_id');
    }

    public function secretarySignature()
    {
        return $this->belongsTo(ManagementSignature::class, 'secretary_signature_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

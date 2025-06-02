<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagementSignature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'full_name',
        'position',
        'signature_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'signature_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    public function signature()
    {
        return $this->belongsTo(Asset::class, 'signature_id');
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
    public function getFullNameAttribute()
    {
        return $this->attributes['full_name'] ?? '';
    }
}

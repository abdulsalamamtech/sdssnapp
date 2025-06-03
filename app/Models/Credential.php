<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', // Title of the credential
        'type', // Type of credential (e.g., Professional, Academic)
        'description', // Description of the credential
        'file_id', // Foreign key to the assets table
        'created_by', // User ID or name of the creator
        'updated_by', // User ID or name of the last updater
        'deleted_by' // User ID or name of the deleter
    ];



    /**
     * Get the file associated with the credential.
     */
    public function file()
    {
        return $this->belongsTo(Asset::class, 'file_id');
    }
    /**
     * Get the user who created the credential.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Get the user who last updated the credential.
     */
    public function updatedBy()
    {

        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the credential.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


}

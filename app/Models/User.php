<?php

namespace App\Models;

use App\Models\Api\Certificate;
use App\Models\Api\Project;
use App\Models\Api\Social;
use App\Models\Assets;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are manormcore iceland tousled brook viral artisan.ss assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'other_name',
        'security_question',
        'answer',
        'phone_number',
        'gender',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'membership_status',
        'role',             // ['user', 'moderator', 'admin', 'super-admin']
        'assigned_by',

        'email_verified',
        'email_verified_at',

        'profession',
        'organization',
        'organization_category',
        'organization_role',
        'organization_name',
        'asset_id',
        'qualification',
        'course'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_question',
        'answer'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    // Social Media
    public function social()
    {
        return $this->hasOne(Social::class, 'user_id', 'id');
    }

    // Has many certificates
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'belong_to', 'id');
    }

    // Certificates added by admin
    public function certificateAdded()
    {
        return $this->hasMany(Certificate::class, 'added_by', 'id');
    }


    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Picture Image
    // public function picture()
    // {
    //     return $this->belongsTo(Assets::class, 'asset_id');
    // }
    public function picture()
    {
        return $this->hasOne(UserPicture::class, 'user_id');
    }


    
    /**
     * The assets that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function assets()
    // {
    //     return $this->belongsToMany(Assets::class, 'user_pictures', 'user_id', 'asset_id');
    // }
}

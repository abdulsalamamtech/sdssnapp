<?php

namespace App\Models\Api;

use App\Models\Assets;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Podcast extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'banner_id',
        'title',
        'slug', // [title]
        'description',
        'category', //['audio', 'video']
        'video_url',
        'audio_url',
        'tags',
        'views',
        'likes',
        'shares',
        'deleted_by'
    ];

    // protected $casts = [
    //     'tags' => 'array',
    // ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }



    /**
     * Get the admin who deleted the post.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the comments for the post.
     */
    public function podcastComments()
    {
        return $this->hasMany(PodcastComment::class, 'podcast_id');
    }

    // Banner Image
    public function banner()
    {
        return $this->belongsTo(Assets::class, 'banner_id');
    }
}

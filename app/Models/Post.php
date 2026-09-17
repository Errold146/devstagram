<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'github_url',
        'site_url',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'name', 'username', 'profile_image']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('user', 'replies')->latest();
    }

    public function commentsCount(): int
    {
        return $this->comments()->get()->sum(fn (Comment $comment) => 1 + $comment->countAllReplies());
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function checkLike(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}

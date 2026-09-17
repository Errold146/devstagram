<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'comment',
    ];

    public function user(): BelongsTo
    {
        // 'id' must stay selected: eager loading (with('user')) matches results by it.
        return $this->belongsTo(User::class)->select(['id', 'name', 'username', 'profile_image']);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies')->oldest();
    }

    public function countAllReplies(): int
    {
        return $this->replies->reduce(fn (int $carry, Comment $reply) => $carry + 1 + $reply->countAllReplies(), 0);
    }

    /**
     * Id of the top-level comment this one ultimately belongs to (itself if it is top-level).
     */
    public function rootId(): int
    {
        return $this->parent_id ? $this->parent->rootId() : $this->id;
    }

    /**
     * All descendant replies (direct and nested), flattened and sorted chronologically,
     * so the UI can display a single reply level instead of ever-growing indentation.
     *
     * @return Collection<int, Comment>
     */
    public function allRepliesFlattened(): Collection
    {
        return $this->replies
            ->flatMap(fn (Comment $reply) => collect([$reply])->merge($reply->allRepliesFlattened()))
            ->sortBy('created_at')
            ->values();
    }
}

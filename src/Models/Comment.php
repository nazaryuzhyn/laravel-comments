<?php

namespace Comments\Models;

use Carbon\Carbon;
use Comments\Models\Scopes\CommentScope;
use Comments\Traits\Commentable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Comment.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $comment
 * @property boolean $is_approved
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Models
 */
class Comment extends Model
{
    use Commentable;
    use CommentScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'comment',
        'is_approved'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_approved' => 'boolean'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string, string>
     */
    protected $with = [
        'commentator',
    ];

    /**
     * Related commentable.
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Related commentator.
     *
     * @return BelongsTo
     * @throws Exception
     */
    public function commentator(): BelongsTo
    {
        return $this->belongsTo($this->getModelName(), 'user_id');
    }

    /**
     * Approve comment.
     *
     * @return $this
     */
    public function approve(): static
    {
        $this->update([
            'is_approved' => true,
        ]);

        return $this;
    }

    /**
     * Disapprove comment.
     *
     * @return $this
     */
    public function disapprove(): static
    {
        $this->update([
            'is_approved' => false,
        ]);

        return $this;
    }

    /**
     * Get model name.
     *
     * @return string
     * @throws Exception
     */
    protected function getModelName(): string
    {
        if (config('comments.user_model')) {
            return config('comments.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the commentator model name.');
    }
}

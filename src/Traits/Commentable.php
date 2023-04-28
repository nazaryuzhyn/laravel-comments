<?php

namespace Comments\Traits;

use Comments\Contracts\Commentator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasComments.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Traits
 */
trait Commentable
{
    /**
     * Related comments.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(config('comments.comment_model'), 'commentable');
    }

    /**
     * Attach a comment to this model.
     *
     * @param string $comment
     * @return Model
     */
    public function comment(string $comment): Model
    {
        return $this->commentFromUser(auth()->user(), $comment);
    }

    /**
     * Attach a comment to this model from user.
     *
     * @param Model|null $user
     * @param string $comment
     * @return Model
     */
    public function commentFromUser(?Model $user, string $comment): Model
    {
        $commentModel = config('comments.comment_model');

        $comment = new $commentModel([
            'commentable_id' => $this->getKey(),
            'commentable_type' => get_class(),
            'user_id' => is_null($user) ? null : $user->getKey(),
            'comment' => $comment,
            'is_approved' => ($user instanceof Commentator) ? !$user->isNeedsCommentApproval() : false,
        ]);

        return $this->comments()->save($comment);
    }
}

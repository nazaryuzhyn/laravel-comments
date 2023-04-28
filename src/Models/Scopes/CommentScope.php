<?php

namespace Comments\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CommentScope.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Models\Scopes
 */
trait CommentScope
{
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', '=', true);
    }

    public function scopeDisapproved(Builder $query): Builder
    {
        return $query->where('is_approved', '=', false);
    }
}

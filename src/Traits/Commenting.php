<?php

namespace Comments\Traits;

/**
 * Trait Commenting.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Traits
 */
trait Commenting
{
    /**
     * Needs comment approval.
     *
     * @var bool
     */
    protected bool $needsCommentApproval = false;

    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @return bool
     */
    public function isNeedsCommentApproval(): bool
    {
        return $this->needsCommentApproval;
    }
}

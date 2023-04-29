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
     * Check if a comment for a specific model needs to be auto approved.
     *
     * @return bool
     */
    public function commentApproved(): bool
    {
        if ($this->shouldBeAutoCommentApproval()) {
            return $this->autoCommentApproval;
        }

        return false;
    }

    /**
     * Check if auto comment approval.
     *
     * @return bool
     */
    protected function shouldBeAutoCommentApproval(): bool
    {
        return isset($this->autoCommentApproval);
    }
}

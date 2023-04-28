<?php

namespace Comments\Contracts;

/**
 * Interface Commentator.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Contracts
 */
interface Commentator
{
    /**
     * Check if a comment for a specific model needs to be approved.
     *
     * @return bool
     */
    public function isNeedsCommentApproval(): bool;
}

<?php

namespace Comments\Tests\Models;

use Comments\Traits\Commenting;
use Illuminate\Foundation\Auth\User;
use Comments\Contracts\Commentator;

/**
 * Class ApprovedUser.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Tests\Models
 */
class ApprovedUser extends User implements Commentator
{
    use Commenting;

    protected $table = 'users';

    protected bool $autoCommentApproval = true;
}

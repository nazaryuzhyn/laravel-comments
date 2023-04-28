<?php

namespace Comments\Tests\Models;

use Comments\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Tests\Models
 */
class Article extends Model
{
    use Commentable;

    protected $guarded = [];
}

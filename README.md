# Laravel Comments

Comments package for Laravel.

### Features

* Simple package to use and install
* Creating Comments
* Approving Comments
* Auto Approve Comments
* Retrieving Comments

## Installation

You can install the package via composer:

    composer require nazaryuzhyn/laravel-comments

### Publish Migration

Add migration to your project:

    php artisan vendor:publish --provider="Comments\CommentsServiceProvider" --tag=migrations

After the migration has been published you can create the comments table by running the migrations:

    php artisan migrate

### Publish Package Configs

In your terminal type:

    php artisan vendor:publish --provider="Comments\CommentsServiceProvider" --tag=config

This command that will be published file with config `config/comments.php`.

See the [full configuration file](https://github.com/nazaryuzhyn/laravel-comments/blob/main/src/config/comments.php)
for more information.

### Register the Package

Register package service provider in `providers` array inside `config/app.php`

```php
'providers' => [
    // ...

    'Comments\CommentsServiceProvider',

],
```

## Usage

### Enable package in models

To let your models be able to receive comments, add the `Comments\Traits\Commentable` trait to the model

```php

namespace App\Models;

use Comments\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Commentable;
    
    // ...
}

```

### Creating Comments

To create a comment on your models, you can use the `comment` method.
It receives the string of the comment that you want to store.

```php

$article = Article::find(1);

$comment = $article->comment('This is a comment...');

```

The comment method returns the newly created comment class.

Sometimes you may also want to create comments on behalf of other users.
You can do this using the `commentFromUser` method and pass your user model to be bound with this comment:

```php

$user = User::find(1);

$article = Article::find(1);

$comment = $article->commentFromUser($user, 'This is a comment from someone else.');

```

### Approving Comments

By default, all comments that you create are not approved.

To approve a single comment, you may use the `approve` method on the Comment model like this:

```php

$article = Article::find(1);

$comment = $article->comments->first();

$comment->approve();

```

### Auto Approve Comments

If you want to approve a comment for a specific user automatically you can let your model implement the following interface and property:

```php

namespace App\Models;

use Comments\Contracts\Commentator;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Commentator
{
    use Commenting;
    
    public bool $needsCommentApproval = false;
    
    // ...
}

```

The needsCommentApproval property received the model instance that you want to add a comment to, 
and you can either return true to mark the comment as not approved, or return false to mark the comment as approved.

### Retrieving Comments

The models that use the `Commentable` trait have access to its comments using the `comments` relation:

```php

$article = Article::find(1);

// Retrieve all comments
$comments = $article->comments;

// Retrieve only approved comments
$approved = $article->comments()->approved()->get();

// Retrieve only disapproved comments
$approved = $article->comments()->disapproved()->get();

```

### Testing

    composer test


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

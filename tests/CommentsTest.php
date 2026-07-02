<?php

declare(strict_types=1);

namespace Comments\Tests;

use Comments\Tests\Models\ApprovedUser;
use Comments\Tests\Models\Article;
use Illuminate\Foundation\Auth\User;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class CommentsTest.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Tests
 */
class CommentsTest extends TestCase
{
    /**
     * Users without commentator interface do not get approved.
     *
     * @return void
     */
    #[Test]
    public function usersWithoutCommentatorInterfaceDoNotGetApproved(): void
    {
        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $article->comment('This is a comment...');

        $comment = $article->comments()->first();

        $this->assertFalse($comment->is_approved);
    }

    /**
     * Models can store comments.
     *
     * @return void
     */
    #[Test]
    public function modelsCanStoreComments(): void
    {
        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $article->comment('This is a comment...');
        $article->comment('This is a different comment...');

        $this->assertCount(2, $article->comments);

        $this->assertSame('This is a comment...', $article->comments[0]->comment);
        $this->assertSame('This is a different comment...', $article->comments[1]->comment);
    }

    /**
     * Comments without users have no relation.
     *
     * @return void
     */
    #[Test]
    public function commentsWithoutUsersHaveNoRelation(): void
    {
        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('This is a comment...');

        $this->assertNull($comment->commentator);
        $this->assertNull($comment->user_id);
    }

    /**
     * Comments can be posted as authenticated users.
     *
     * @return void
     */
    #[Test]
    public function commentsCanBePostedAsAuthenticatedUsers(): void
    {
        $user = User::query()->first();

        auth()->login($user);

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('This is a comment...');

        $this->assertSame($user->toArray(), $comment->commentator->toArray());
    }

    /**
     * Comments can be posted as different users.
     *
     * @return void
     */
    #[Test]
    public function commentsCanBePostedAsDifferentUsers(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->commentFromUser($user, 'This is a comment...');

        $this->assertSame($user->toArray(), $comment->commentator->toArray());
    }

    /**
     * Comments can be approved.
     *
     * @return void
     */
    #[Test]
    public function commentsCanBeApproved(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('This is a comment...');

        $this->assertFalse($comment->is_approved);

        $comment->approve();

        $this->assertTrue($comment->is_approved);
    }

    /**
     * Comments resolve the commented model.
     *
     * @return void
     */
    #[Test]
    public function commentsResolveCommentedModel(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('This is a comment...');

        $this->assertSame($comment->commentable->id, $article->id);
        $this->assertSame($comment->commentable->title, $article->title);
    }

    /**
     * Users can be auto approved.
     *
     * @return void
     */
    #[Test]
    public function usersCanBeAutoApproved(): void
    {
        $user = ApprovedUser::query()->first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->commentFromUser($user, 'This is a comment...');

        $this->assertTrue($comment->is_approved);
    }

    /**
     * Comments have an approved scope.
     *
     * @return void
     */
    #[Test]
    public function commentsHaveAnApprovedScope(): void
    {
        $user = ApprovedUser::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $article->comment('This comment is not approved');
        $article->commentFromUser($user, 'This comment is approved');

        $this->assertCount(2, $article->comments);
        $this->assertCount(1, $article->comments()->approved()->get());

        $this->assertSame('This comment is approved', $article->comments()->approved()->first()->comment);
    }
}

<?php

namespace Comments\Tests;

use Comments\Tests\Models\ApprovedUser;
use Comments\Tests\Models\Article;
use Illuminate\Foundation\Auth\User;

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
     * @test
     * @return void
     */
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
     * @test
     * @return void
     */
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
     * @test
     * @return void
     */
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
     * @test
     * @return void
     */
    public function commentsCanBePostedAsAuthenticatedUsers(): void
    {
        $user = User::query()->first();

        auth()->login($user);

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('this is a comment');

        $this->assertSame($user->toArray(), $comment->commentator->toArray());
    }

    /**
     * Comments can be posted as different users.
     *
     * @test
     * @return void
     */
    public function commentsCanBePostedAsDifferentUsers(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->commentFromUser($user, 'this is a comment');

        $this->assertSame($user->toArray(), $comment->commentator->toArray());
    }

    /**
     * Comments can be approved.
     *
     * @test
     * @return void
     */
    public function commentsCanBeApproved(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('this is a comment');

        $this->assertFalse($comment->is_approved);

        $comment->approve();

        $this->assertTrue($comment->is_approved);
    }

    /**
     * Comments resolve the commented model.
     *
     * @test
     * @return void
     */
    public function commentsResolveCommentedModel(): void
    {
        $user = User::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->comment('this is a comment');

        $this->assertSame($comment->commentable->id, $article->id);
        $this->assertSame($comment->commentable->title, $article->title);
    }

    /**
     * Users can be auto approved.
     *
     * @test
     * @return void
     */
    public function usersCanBeAutoApproved(): void
    {
        $user = ApprovedUser::query()->first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $comment = $article->commentFromUser($user, 'this is a comment');

        $this->assertTrue($comment->is_approved);
    }

    /**
     * Comments have an approved scope.
     *
     * @test
     * @return void
     */
    public function commentsHaveAnApprovedScope(): void
    {
        $user = ApprovedUser::first();

        $article = Article::query()->create([
            'title' => 'Test Article'
        ]);

        $article->comment('this comment is not approved');
        $article->commentFromUser($user, 'this comment is approved');

        $this->assertCount(2, $article->comments);
        $this->assertCount(1, $article->comments()->approved()->get());

        $this->assertSame('this comment is approved', $article->comments()->approved()->first()->comment);
    }
}

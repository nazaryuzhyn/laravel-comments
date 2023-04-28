<?php

namespace Comments\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Schema\Blueprint;
use Comments\CommentsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminated\Testing\TestingTools;

/**
 * Class TestCase.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Tests
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use TestingTools;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpEnvironment($this->app);
        $this->setUpDatabase($this->app);
        $this->createUser();
    }

    /**
     * Get package providers.
     *
     * @param $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            CommentsServiceProvider::class,
        ];
    }

    /**
     * Get Environments.
     *
     * @param $app
     * @return void
     */
    protected function setUpEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:6Cu/ozj4gPtIjmXjr8EdVnGFNsdRqZfHfVjQkmTlg4Y=');
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Set up Database.
     *
     * @param $app
     * @return void
     */
    protected function setUpDatabase($app): void
    {
        include_once __DIR__ . '/../database/migrations/create_comments_table.php.stub';

        (new \CreateCommentsTable())->up();

        $app['db']->connection()
            ->getSchemaBuilder()
            ->create('users', static function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });

        $app['db']->connection()
            ->getSchemaBuilder()
            ->create('articles', static function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->timestamps();
            });
    }

    /**
     * Create user.
     *
     * @return void
     */
    protected function createUser(): void
    {
        User::query()->forceCreate([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password'
        ]);
    }
}

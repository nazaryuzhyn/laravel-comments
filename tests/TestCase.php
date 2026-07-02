<?php

namespace Comments\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Schema\Blueprint;
use Comments\CommentsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TestCase.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Tests
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'sqlite']);
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
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', 'base64:6Cu/ozj4gPtIjmXjr8EdVnGFNsdRqZfHfVjQkmTlg4Y=');
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

        $this->app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
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

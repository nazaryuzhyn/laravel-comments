<?php

namespace Comments;

use Illuminate\Support\ServiceProvider;

/**
 * Class CommentsServiceProvider.
 *
 * @author Nazar Yuzhyn <nazaryuzhyn@gmail.com>
 * @package Comments\Providers
 */
class CommentsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/comments.php', 'comments');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishesConfig();
            $this->publishesMigration();
        }
    }

    /**
     * Publish config.
     *
     * @return void
     */
    protected function publishesConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/comments.php' => config_path('comments.php'),
        ], 'config');
    }

    /**
     * Publish migration.
     *
     * @return void
     */
    protected function publishesMigration(): void
    {
        if (!class_exists('CreateCommentsTable')) {
            $migration = database_path(
                'migrations/' . date('Y_m_d_His', time()) . '_create_comments_table.php'
            );

            $this->publishes([
                __DIR__.'/../database/migrations/create_comments_table.php.stub' => $migration,
            ], 'migrations');
        }
    }
}

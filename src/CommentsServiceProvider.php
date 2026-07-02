<?php

declare(strict_types=1);

namespace Comments;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
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
        $this->publishes([
            __DIR__.'/../database/migrations/create_comments_table.php.stub' => $this->getMigrationFileName(),
        ], 'migrations');
    }

    /**
     * Resolve the migration file name, reusing an already published file if present.
     *
     * @return string
     */
    protected function getMigrationFileName(): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([database_path('migrations') . DIRECTORY_SEPARATOR])
            ->flatMap(fn (string $path): array => (array) $filesystem->glob($path . '*_create_comments_table.php'))
            ->push(database_path('migrations') . DIRECTORY_SEPARATOR . $timestamp . '_create_comments_table.php')
            ->first();
    }
}

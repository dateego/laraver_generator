<?php
namespace Dateego\Generators;

use Closure;
use Dateego\Generators\Commands\MigrationGeneratorCommand;
use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path('generators.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('dateego.migration.generate', function($app) {
            return new MigrationGeneratorCommand(
                $app->make('Dateego\Generators\Generators\Generator'),
                $app->make('Dateego\Generators\Filesystem\Filesystem'),
                $app->make('Dateego\Generators\Compilers\TemplateCompiler'),
                $app->make('migration.repository'),
                $app->make('config')
            );
        });

        $this->commands('dateego.migration.generate');

        // Bind the Repository Interface to $app['migrations.repository']
        $this->app->bind('Illuminate\Database\Migrations\MigrationRepositoryInterface', function($app) {
            return $app['migration.repository'];
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
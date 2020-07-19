<?php

declare(strict_types=1);

namespace TSterker\Solarium;

use Illuminate\Support\ServiceProvider;
use Solarium\Client;

class SolariumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__ . '/../config/solarium.php') ?: $raw;

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('solarium.php')]);
        }

        $this->mergeConfigFrom($source, 'solarium');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    protected function registerFactory(): void
    {
        $this->app->singleton('solarium.factory', function ($app) {
            return new SolariumFactory;
        });

        $this->app->alias('solarium.factory', SolariumFactory::class);
    }

    protected function registerManager(): void
    {
        $this->app->singleton('solarium', function ($app) {
            $config = $app['config'];
            $factory = $app['solarium.factory'];

            return new SolariumManager($config, $factory);
        });

        $this->app->alias('solarium', SolariumManager::class);
    }

    protected function registerBindings(): void
    {
        $this->app->bind('solarium.connection', function ($app) {
            $manager = $app['solarium'];
            return $manager->connection();
        });

        $this->app->alias('solarium.connection', Client::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'solarium.factory',
            'solarium',
            'solarium.connection',
        ];
    }
}

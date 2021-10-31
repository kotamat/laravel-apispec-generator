<?php
declare(strict_types=1);

namespace ApiSpec;


use ApiSpec\Builders\BuilderInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ApiSpecServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/apispec.php', 'apispec'
        );
        $this->app->bind(BuilderInterface::class, function (Application $app) {
            $builderClass = $app->make('config')->get('apispec.builder');

            return $app->make($builderClass);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/apispec.php' => config_path('apispec.php'),
        ]);
    }
}

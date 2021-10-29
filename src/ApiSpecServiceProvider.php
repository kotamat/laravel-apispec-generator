<?php

namespace ApiSpec;


use ApiSpec\Builders\BuilderInterface;
use Illuminate\Support\ServiceProvider;

class ApiSpecServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BuilderInterface::class,function($app,$name,array $config){
            return new $config['builder'];
        });
    }
}
<?php

namespace PIRB\ThumbnailGenerator;

use Illuminate\Support\ServiceProvider;

class ThumbnailGeneratorProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/thumbnailgenerator.php' => config_path('thumbnailgenerator.php'),
        ]);
    }


    public function register()
    {
        if (method_exists(\Illuminate\Foundation\Application::class, 'singleton')) {
            $this->app->singleton('thumbnailgenerator', function ($app) {
                return new ThumbnailGenerator;
            });
        } else {
            $this->app['thumbnailgenerator'] = $this->app->share(function ($app) {
                return new ThumbnailGenerator;
            });
        }
    }
}

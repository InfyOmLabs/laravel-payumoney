<?php
namespace InfyOmLabs\Payu;

use Illuminate\Support\ServiceProvider;
use InfyOmLabs\Payu\Commands\PublishAsset;

/**
 * Class PayuMoneyAppServiceProvider
 */
class PayuMoneyAppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-payumoney.php' => config_path('laravel-payumoney.php')
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-payumoney');
    }
    
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-payu.php', 'laravel-payumoney');

        $this->app->make('InfyOm\Payu\PayuMoneyController');

        $this->app->singleton('laravel-payumoney:publish', function ($app) {
            return new PublishAsset();
        });

        $this->commands([
            'laravel-payumoney:publish',
        ]);
    }
}

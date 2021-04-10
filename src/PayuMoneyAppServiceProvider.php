<?php
namespace InfyOm\Payu;

use Illuminate\Support\ServiceProvider;
use InfyOm\Payu\Commands\PublishAsset;

/**
 * Class PayuMoneyAppServiceProvider
 */
class PayuMoneyAppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/payu.php' => config_path('payu.php')
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
    
    public function register()
    {
        $this->app->make('InfyOm\Payu\PayuMoneyController');

        $this->app->singleton('payumoney:publish', function ($app) {
            return new PublishAsset();
        });

        $this->commands([
            'payumoney:publish',
        ]);
    }
}

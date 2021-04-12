<?php

namespace InfyOm\Payu\Commands;

use Illuminate\Support\Facades\File;

/**
 * Class PublishAsset
 */
class PublishAsset extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laravel-payumoney:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Config|Views|Controller';

    public function handle()
    {
        $this->info('payu.php config file published.');
        $config = file_get_contents(__DIR__.'./../../config/payu.php');
        $this->createFile(config_path('/'), 'payu.php', $config);

        \Artisan::call('vendor:publish --provider="InfyOm\Payu\PayuMoneyAppServiceProvider" --tag="views"');

        $this->info('view files published.');
        $views = file_get_contents(__DIR__.'./../../config/payu.php');
        if (!\File::isDirectory(resource_path('views/payumoney'))) {
            \File::makeDirectory(resource_path('views/payumoney/'));
        }

        \File::copyDirectory(
            base_path('vendor/infyomlabs/laravel-payumoney/views/payumoney'),
            resource_path('views/payumoney')
        );

        $this->info('PayuMoneyController published.');
        $templateData = file_get_contents(__DIR__.'/../../stubs/PayuMoneyController.stub');


        $this->info('Public assets published.');
        if (!\File::isDirectory(public_path('payumoney'))) {
            \File::makeDirectory(public_path('payumoney'));
        }

        \File::copy(
            base_path('vendor/infyomlabs/laravel-payumoney/assets/infyom-logo.png'),
            public_path('payumoney/infyom-logo.png')
        );

        \File::copy(
            base_path('vendor/infyomlabs/laravel-payumoney/assets/payu.css'),
            public_path('payumoney/payu.css')
        );


        $this->info('PayuMoneyController published.');
        $this->createFile(app_path('Http/Controllers/'), 'PayuMoneyController.php', $templateData);
    }

    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path.$fileName;

        file_put_contents($path, $contents);
    }
}

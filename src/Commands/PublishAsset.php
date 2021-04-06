<?php

namespace InfyOm\Payu\Commands;

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
    protected $name = 'payumoney:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets';
    
    public function handle()
    {
        // publish view
        
        $this->info('payu.php config file published.');
        \Artisan::call('vendor:publish --provider="InfyOm\Payu\PayuMoneyAppServiceProvider" --tag="config"');

        $this->info('view files published.');
        \Artisan::call('vendor:publish --provider="InfyOm\Payu\PayuMoneyAppServiceProvider" --tag="views"');

        $this->info('PayuMoneyController published.');
        $templateData = file_get_contents(__DIR__.'/../../stubs/PayuMoneyController.stub');

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

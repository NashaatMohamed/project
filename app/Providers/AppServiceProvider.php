<?php

namespace App\Providers;

use App\Services\Language\Drivers\Translation;
use App\Services\Language\Scanner;
use App\Services\Language\TranslationManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Set default string lenght
        // Schema::defaultStringLength(191);

        $this->app->singleton(Scanner::class, function () {
            $config = $this->app['config']['translation'];

            return new Scanner(new Filesystem, $config['scan_paths'], $config['translation_methods']);
        });

        $this->app->singleton(Translation::class, function ($app) {
            return (new TranslationManager($app, $app['config']['translation'], $app->make(Scanner::class)))->resolve();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

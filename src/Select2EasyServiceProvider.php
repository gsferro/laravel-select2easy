<?php

namespace Select2Easy;

use Illuminate\Support\ServiceProvider;

class Select2EasyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/routes/web.php' );

        $this->loadViewsFrom(__DIR__."/resources/views", "select2easy");
        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/select2easy'),
        ]);
    }
}

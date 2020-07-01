<?php

namespace Gsferro\Select2Easy\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . "/../resources/views", "select2easy");
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/select2easy'),
        ]);

        // Alias blade
        Blade::directive("select2easyCss", function(){
            return "<link href='/vendor/select2easy/select2/css/select2.css' rel='stylesheet' type='text/css'/>";
        });

        Blade::directive("select2easyJs", function(){
            return 	"<script src='/vendor/select2easy/js/select2easy.js'></script>".
                    "<script src='/vendor/select2easy/select2/js/select2.js'></script>";
        });
    }
}

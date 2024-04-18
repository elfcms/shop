<?php

namespace Elfcms\Shop\Providers;

use Elfcms\Shop\Http\Middleware\ApiAuthenticate;
use Elfcms\Shop\Http\Middleware\ApiJson;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ElfcmsModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $moduleDir = dirname(__DIR__);

        $this->loadRoutesFrom($moduleDir . '/routes/web.php');
        $this->loadRoutesFrom($moduleDir . '/routes/api_v1.php');
        $this->loadViewsFrom($moduleDir . '/resources/views', 'elfcms');
        $this->loadMigrationsFrom($moduleDir . '/database/migrations');

        $this->loadTranslationsFrom($moduleDir . '/resources/lang', 'shop');

        $this->publishes([
            $moduleDir . '/resources/lang' => resource_path('lang/elfcms/shop'),
        ], 'lang');

        $this->publishes([
            $moduleDir . '/config/shop.php' => config_path('elfcms/shop.php'),
            $moduleDir . '/config/selling.php' => config_path('elfcms/selling.php'),
        ], 'config');

        $this->publishes([
            $moduleDir . '/resources/views/admin' => resource_path('views/elfcms/admin'),
        ], 'admin');

        $this->publishes([
            $moduleDir . '/public/admin' => public_path('elfcms/admin/modules/shop/'),
        ], 'admin');

        $this->publishes([
            $moduleDir.'/resources/views/components' => resource_path('views/elfcms/modules/shop/components'),
        ],'components');

        $router->middlewareGroup('api_auth', array(
            ApiAuthenticate::class
        ));

        $router->middlewareGroup('api_json', array(
            ApiJson::class
        ));

        //Blade::component('gallery-slider', \Elfcms\Gallery\View\Components\Slider::class);
        //Blade::component('gallery-gallery', \Elfcms\Gallery\View\Components\Gallery::class);
    }
}

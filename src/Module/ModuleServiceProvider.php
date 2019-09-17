<?php

namespace BristolSU\Support\Module\ServiceProvider;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleServiceProvider
 *
 * Does not defer
 * Map web and API routes
 * Registers Translations
 * Registers Config
 * Registers Views
 * Registers Factories
 * Loads Migrations
 * Registers Permissions
 *
 *
 * @package BristolSU\Support\Module
 */
abstract class ModuleServiceProvider extends ServiceProvider
{

    protected $defer = false;

    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
//        $this->registerViews();
//        $this->registerFactories();
//        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
//        $this->mapWebRoutes();
//        $this->mapApiRoutes();

    }

    public function registerTranslations()
    {
        $this->loadTranslationsFrom($this->baseDirectory() . '/../resources/lang', $this->alias());
    }

    protected function registerConfig()
    {
        $this->publishes([$this->baseDirectory() . '/../config/config.php' => config_path($this->alias() . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->baseDirectory() . '/../config/config.php', $this->alias()
        );
    }

//    public function registerViews()
//    {
//        $viewPath = resource_path('views/modules/fileupload');
//
//        $sourcePath = __DIR__ . '/../Resources/views';
//
//        $this->publishes([
//            $sourcePath => $viewPath
//        ], 'views');
//
//        $this->loadViewsFrom(array_merge(array_map(function ($path) {
//            return $path . '/modules/fileupload';
//        }, Config::get('view.paths')), [$sourcePath]), 'fileupload');
//    }
//
//    public function registerFactories()
//    {
//        if (!app()->environment('production')) {
//            app(Factory::class)->load(__DIR__ . '/../Database/factories');
//        }
//    }
//
//    public function mapWebRoutes()
//    {
//        Route::prefix('{activity_slug}/{module_instance_slug}')
//            ->middleware(['web', 'module'])
//            ->namespace($this->namespace)
//            ->group(__DIR__ . '/../Routes/web.php');
//    }
//
//    public function mapApiRoutes()
//    {
//        Route::prefix('{activity_slug}/{module_instance_slug}')
//            ->middleware(['api', 'module'])
//            ->namespace($this->namespace)
//            ->group(__DIR__ . '/../Routes/api.php');
//    }

    /**
     * @return string
     */
    public function baseDirectory() {
        // TODO Change to composer
        return '';
    }

    /**
     * @return string
     */
    abstract public function alias(): string;

}

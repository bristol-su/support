<?php

namespace BristolSU\Support\Translation;

use BristolSU\Support\Translation\Http\Controllers\TranslationController;
use BristolSU\Support\Translation\Locale\Strategies\BodyDetectionStrategy;
use BristolSU\Support\Translation\Locale\Strategies\CookieDetectionStrategy;
use BristolSU\Support\Translation\Locale\DetectionStrategyStore;
use BristolSU\Support\Translation\Locale\Strategies\HeaderDetectionStrategy;
use BristolSU\Support\Translation\Translate\Handlers\Cache;
use BristolSU\Support\Translation\Translate\TranslationManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(DetectionStrategyStore::class);
        $this->app->singleton('portal-translation', function($app) {
            return new TranslationManager($app);
        });
    }

    public function boot()
    {
        ($this->app->make(DetectionStrategyStore::class))->registerFirst(BodyDetectionStrategy::class);
        ($this->app->make(DetectionStrategyStore::class))->register(CookieDetectionStrategy::class);
        ($this->app->make(DetectionStrategyStore::class))->registerLast(HeaderDetectionStrategy::class);

        Route::post('/_translate', [TranslationController::class, 'translate'])->name('translator.translate');

    }

}

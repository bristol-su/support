<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Middleware\InjectActivity;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceServiceRepository;
use BristolSU\Support\ModuleInstance\Contracts\Connection\ModuleInstanceServiceRepository as ModuleInstanceServiceRepositoryContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityInstanceEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore as CommandStoreContract;
use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore as ModuleSettingsStoreContract;
use BristolSU\Support\ModuleInstance\Evaluator\ActivityInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Middleware\InjectModuleInstance;
use BristolSU\Support\ModuleInstance\Scheduler\CommandStore;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\ModuleInstance\Settings\ModuleSettingsStore;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Module Instance Service Provider
 */
class ModuleInstanceServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Bind implementations to contracts
     * - Set up the module settings store as a singleton
     * - Set up the command store as a singleton
     */
    public function register()
    {
        $this->app->bind(ModuleInstanceRepositoryContract::class, ModuleInstanceRepository::class);
        $this->app->bind(ActivityEvaluatorContract::class, ActivityInstanceEvaluator::class);
        $this->app->bind(ModuleInstanceEvaluatorContract::class, ModuleInstanceEvaluator::class);
        $this->app->bind(EvaluationContract::class, Evaluation::class);
        $this->app->bind(ModuleInstanceServiceRepositoryContract::class, ModuleInstanceServiceRepository::class);
        $this->app->singleton(ModuleSettingsStoreContract::class, ModuleSettingsStore::class);
        $this->app->singleton(CommandStoreContract::class, CommandStore::class);
    }

    /**
     * Boot
     * 
     * - Push middleware to module group
     * - Route model binding for a module_instance_setting
     * - Route model binding for a module_instance_service
     * - Route model binding for the module_instance_slug
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('module', InjectModuleInstance::class);

        Route::bind('module_instance_setting', function ($id) {
            return ModuleInstanceSetting::findOrFail($id);
        });

        Route::bind('module_instance_service', function ($id) {
            return ModuleInstanceService::findOrFail($id);
        });

        Route::bind('module_instance_slug', function ($slug, $route) {
            $activity = $route->parameter('activity_slug');
            return ModuleInstance::where('slug', $slug)
                ->whereHas('activity', function ($query) use ($activity) {
                    $query->where('slug', $activity->slug);
                })
                ->firstOrFail();
        });
                
    }

}
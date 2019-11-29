<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Middleware\InjectActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityInstanceEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use BristolSU\Support\ModuleInstance\Evaluator\ActivityInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Middleware\InjectModuleInstance;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleInstanceServiceProvider
 * @package BristolSU\Support\ModuleInstance
 */
class ModuleInstanceServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ModuleInstanceRepositoryContract::class, ModuleInstanceRepository::class);
        $this->app->bind(ActivityEvaluatorContract::class, ActivityInstanceEvaluator::class);
        $this->app->bind(ModuleInstanceEvaluatorContract::class, ModuleInstanceEvaluator::class);
        $this->app->bind(EvaluationContract::class, Evaluation::class);
    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('module', InjectModuleInstance::class);

        Route::bind('module_instance_setting', function ($id) {
            return ModuleInstanceSettings::findOrFail($id);
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
<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use BristolSU\Support\ModuleInstance\Evaluator\ActivityEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use Illuminate\Support\ServiceProvider;

class ModuleInstanceServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ModuleInstanceRepositoryContract::class, ModuleInstanceRepository::class);
        $this->app->bind(ActivityEvaluatorContract::class, ActivityEvaluator::class);
        $this->app->bind(ModuleInstanceEvaluatorContract::class, ModuleInstanceEvaluator::class);
        $this->app->bind(EvaluationContract::class, Evaluation::class);
    }

}
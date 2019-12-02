<?php


namespace BristolSU\Support\ModuleInstance\Facade;


use Illuminate\Support\Facades\Facade;

class ModuleInstanceEvaluator extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator::class;
    }

}
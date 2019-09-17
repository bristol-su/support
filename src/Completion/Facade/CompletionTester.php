<?php


namespace BristolSU\Support\Completion\Facade;


use BristolSU\Support\Completion\Contracts\CompletionTester as CompletionTesterContract;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Support\Facades\Facade;

/**
 * Class CompletionTester
 * @method static bool evaluate(ModuleInstance $moduleInstance, int $modelId = null) Test if the module instance has been completed by the given model ID
 * @package BristolSU\Support\Completion\Facade
 */
class CompletionTester extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CompletionTesterContract::class;
    }
}

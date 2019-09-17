<?php


namespace BristolSU\Support\Completion\Contracts;


use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

interface CompletionTester
{

    public function evaluate(ModuleInstance $moduleInstance, $modelId = null): bool;

}

<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

interface ModuleInstanceEvaluator
{

    public function evaluateParticipant(ModuleInstance $moduleInstance);

    public function evaluateAdministrator(ModuleInstance $moduleInstance);
}

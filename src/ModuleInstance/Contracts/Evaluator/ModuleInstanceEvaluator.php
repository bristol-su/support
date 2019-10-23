<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

/**
 * Interface ModuleInstanceEvaluator
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface ModuleInstanceEvaluator
{

    /**
     * @param ModuleInstance $moduleInstance
     * @return mixed
     */
    public function evaluateParticipant(ModuleInstance $moduleInstance);

    /**
     * @param ModuleInstance $moduleInstance
     * @return mixed
     */
    public function evaluateAdministrator(ModuleInstance $moduleInstance);
}

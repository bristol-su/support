<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;

interface ActivityEvaluator
{

    public function evaluateAdministrator(Activity $activity);

    public function evaluateParticipant(Activity $activity);

}

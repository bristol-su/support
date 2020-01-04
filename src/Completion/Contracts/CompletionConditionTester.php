<?php


namespace BristolSU\Support\Completion\Contracts;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use \BristolSU\ControlDB\Contracts\Models\User;

/**
 * Interface CompletionConditionTester
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionTester
{

    public function evaluate(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): bool;

}

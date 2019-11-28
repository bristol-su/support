<?php


namespace BristolSU\Support\Completion\Contracts;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use \BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface CompletionConditionTester
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionTester
{

    public function evaluate(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): bool;

}

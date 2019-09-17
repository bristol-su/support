<?php

namespace BristolSU\Support\Completion\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;

/**
 * Test if a module instance is complete
 */
interface CompletionConditionTester
{

    /**
     * Tests if the completion condition instance is complete for the given activity instance
     * 
     * @param ActivityInstance $activityInstance Activity instance to test
     * @param CompletionConditionInstance $completionConditionInstance Completion condition instance to test
     * @return bool If the condition is complete or not
     */
    public function evaluate(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): bool;

}

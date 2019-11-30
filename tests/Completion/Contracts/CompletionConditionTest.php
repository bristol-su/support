<?php

namespace BristolSU\Support\Tests\Completion\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionTest extends TestCase
{

    /** @test */
    public function moduleAlias_returns_the_module_alias(){
        $condition = new DummyCondition('alias1');
        $this->assertEquals('alias1', $condition->moduleAlias());
    }
    
}

class DummyCondition extends CompletionCondition {

    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        // TODO: Implement isComplete() method.
    }

    public function options(): array
    {
        // TODO: Implement options() method.
    }

    public function name(): string
    {
        // TODO: Implement name() method.
    }

    public function description(): string
    {
        // TODO: Implement description() method.
    }

    public function alias(): string
    {
        // TODO: Implement alias() method.
    }
}
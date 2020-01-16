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
    
    /** @test */
    public function percentage_returns_100_if_condition_is_complete(){
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(ModuleInstance::class)->reveal();
        
        $condition = new DummyCondition('alias1');
        $condition->complete();
        
        $this->assertTrue($condition->isComplete([], $activityInstance, $moduleInstance));
        $this->assertEquals(100, $condition->percentage([], $activityInstance, $moduleInstance));
    }

    /** @test */
    public function percentage_returns_0_if_condition_is_not_complete(){
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(ModuleInstance::class)->reveal();
        $condition = new DummyCondition('alias1');

        $this->assertFalse($condition->isComplete([], $activityInstance, $moduleInstance));
        $this->assertEquals(0, $condition->percentage([], $activityInstance, $moduleInstance));
    }
    
}

class DummyCondition extends CompletionCondition {

    protected $complete = false;
    
    public function complete()
    {
        $this->complete = true;
    }
    
    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        return $this->complete;
    }

    public function options(): array
    {
    }

    public function name(): string
    {
    }

    public function description(): string
    {
    }

    public function alias(): string
    {
    }
}
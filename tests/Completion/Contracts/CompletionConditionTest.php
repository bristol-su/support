<?php

namespace BristolSU\Support\Tests\Completion\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class CompletionConditionTest extends TestCase
{
    /** @test */
    public function module_alias_returns_the_module_alias()
    {
        $condition = new DummyCondition('alias1');
        $this->assertEquals('alias1', $condition->moduleAlias());
    }
    
    /** @test */
    public function percentage_returns_100_if_condition_is_complete()
    {
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(ModuleInstance::class)->reveal();
        
        $condition = new DummyCondition('alias1');
        $condition->complete();
        
        $this->assertTrue($condition->isComplete([], $activityInstance, $moduleInstance));
        $this->assertEquals(100, $condition->percentage([], $activityInstance, $moduleInstance));
    }

    /** @test */
    public function percentage_returns_0_if_condition_is_not_complete()
    {
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(ModuleInstance::class)->reveal();
        $condition = new DummyCondition('alias1');

        $this->assertFalse($condition->isComplete([], $activityInstance, $moduleInstance));
        $this->assertEquals(0, $condition->percentage([], $activityInstance, $moduleInstance));
    }
    
    /** @test */
    public function to_array_returns_an_array_representation_of_the_condition()
    {
        $condition = new DummyCondition('alias1');
        
        $array = $condition->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertEquals('Name1', $array['name']);
        
        $this->assertArrayHasKey('description', $array);
        $this->assertEquals('Desc1', $array['description']);

        $this->assertArrayHasKey('alias', $array);
        $this->assertEquals('alias1', $array['alias']);
        
        $this->assertArrayHasKey('options', $array);
        $this->assertIsArray($array['options']);
        $this->assertArrayHasKey('schema', $array['options']);
    }
    
    /** @test */
    public function to_json_returns_a_json_representation_of_the_condition()
    {
        $condition = new DummyCondition('alias1');

        $json = $condition->toJson();

        $this->assertJson($json);
        
        $arrayable = json_decode($json, true);

        $this->assertArrayHasKey('name', $arrayable);
        $this->assertEquals('Name1', $arrayable['name']);

        $this->assertArrayHasKey('description', $arrayable);
        $this->assertEquals('Desc1', $arrayable['description']);

        $this->assertArrayHasKey('alias', $arrayable);
        $this->assertEquals('alias1', $arrayable['alias']);

        $this->assertArrayHasKey('options', $arrayable);
        $this->assertIsArray($arrayable['options']);
        $this->assertArrayHasKey('schema', $arrayable['options']);
    }
}

class DummyCondition extends CompletionCondition
{
    protected $complete = false;
    
    public function complete()
    {
        $this->complete = true;
    }
    
    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        return $this->complete;
    }

    public function options(): Form
    {
        return new Form();
    }

    public function name(): string
    {
        return 'Name1';
    }

    public function description(): string
    {
        return 'Desc1';
    }

    public function alias(): string
    {
        return 'alias1';
    }
}

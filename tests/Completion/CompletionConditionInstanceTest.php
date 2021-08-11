<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionInstanceTest extends TestCase
{
    /** @test */
    public function name_returns_the_completion_condition_instance_name()
    {
        $cCI = CompletionConditionInstance::factory()->create(['name' => 'name1']);
        $this->assertEquals('name1', $cCI->name());
    }

    /** @test */
    public function settings_returns_the_completion_condition_instance_settings()
    {
        $cCI = CompletionConditionInstance::factory()->create(['settings' => ['val' => 'settings1']]);
        $this->assertEquals(['val' => 'settings1'], $cCI->settings());
    }

    /** @test */
    public function alias_returns_the_completion_condition_instance_alias()
    {
        $cCI = CompletionConditionInstance::factory()->create(['alias' => 'alias1']);
        $this->assertEquals('alias1', $cCI->alias());
    }

    /** @test */
    public function it_has_a_module_instance()
    {
        $cCI = CompletionConditionInstance::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create(['completion_condition_instance_id' => $cCI->id]);

        $this->assertModelEquals($moduleInstance, $cCI->moduleInstance);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $completionConditionInstance = CompletionConditionInstance::factory()->create(['name' => 'OldName']);

        $completionConditionInstance->name = 'NewName';
        $completionConditionInstance->save();

        $this->assertEquals(1, $completionConditionInstance->revisionHistory->count());
        $this->assertEquals($completionConditionInstance->id, $completionConditionInstance->revisionHistory->first()->revisionable_id);
        $this->assertEquals(CompletionConditionInstance::class, $completionConditionInstance->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $completionConditionInstance->revisionHistory->first()->key);
        $this->assertEquals('OldName', $completionConditionInstance->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $completionConditionInstance->revisionHistory->first()->new_value);
    }
}

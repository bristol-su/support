<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionManager;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionManagerTest extends TestCase
{

    /** @test */
    public function a_completion_condition_can_be_registered_and_retrieved(){
        
        $manager = new CompletionConditionManager;
        $manager->register('moduleAlias1', 'alias1', 'className1');
        
        $this->assertEquals('className1', $manager->getClassFromAlias('moduleAlias1', 'alias1'));
    }
    
    /** @test */
    public function all_completion_conditions_for_a_module_can_be_retrieved(){
        $manager = new CompletionConditionManager;
        $manager->register('moduleAlias1', 'alias1', 'className1');
        $manager->register('moduleAlias1', 'alias2', 'className2');
        $manager->register('moduleAlias2', 'alias3', 'className3');

        $this->assertEquals([
            'alias1' => 'className1',
            'alias2' => 'className2'
        ], $manager->getForModule('moduleAlias1'));
    }
    
    /** @test */
    public function global_completion_conditions_are_added_to_conditions_from_a_module(){
        $manager = new CompletionConditionManager;
        $manager->registerGlobalCondition('alias1', 'className1');

        $this->assertEquals('className1', $manager->getClassFromAlias('moduleAlias1', 'alias1'));
    }
    
    /** @test */
    public function a_module_completion_condition_takes_priority_over_a_global_condition(){
        $manager = new CompletionConditionManager;
        $manager->register('moduleAlias1', 'alias1', 'className1');
        $manager->registerGlobalCondition('alias1', 'className2');

        $this->assertEquals('className1', $manager->getClassFromAlias('moduleAlias1', 'alias1'));
    }
    
    /** @test */
    public function getForModule_returns_an_empty_array_if_no_conditions_registered(){
        $manager = new CompletionConditionManager;
        $this->assertEquals([], $manager->getForModule('moduleAlias1'));
    }
    
    /** @test */
    public function getClassFromAlias_throws_an_exception_if_alias_not_found(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Completion Condition alias [alias1] not found for module [module1]');

        $manager = new CompletionConditionManager;
        $manager->getClassFromAlias('module1', 'alias1');
    }
    
}
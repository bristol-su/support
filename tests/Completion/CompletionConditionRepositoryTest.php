<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionRepository;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionFactory;
use BristolSU\Support\Completion\Contracts\CompletionConditionManager;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionRepositoryTest extends TestCase
{
    /** @test */
    public function get_by_alias_gets_and_resolves_a_completion_condition()
    {
        $manager = $this->prophesize(CompletionConditionManager::class);
        $factory = $this->prophesize(CompletionConditionFactory::class);
        $completionCondition = $this->prophesize(CompletionCondition::class);

        $manager->getClassFromAlias('module1', 'alias1')->willReturn('class1');
        $factory->createCompletionConditionFromClassName('class1', 'module1')->willReturn($completionCondition->reveal());
        $repository = new CompletionConditionRepository($manager->reveal(), $factory->reveal());
        
        $this->assertEquals($completionCondition->reveal(), $repository->getByAlias('module1', 'alias1'));
    }
    
    /** @test */
    public function get_all_for_module_gets_and_resolves_all_completion_conditions_for_a_module()
    {
        $manager = $this->prophesize(CompletionConditionManager::class);
        $factory = $this->prophesize(CompletionConditionFactory::class);
        $completionCondition1 = $this->prophesize(CompletionCondition::class);
        $completionCondition2 = $this->prophesize(CompletionCondition::class);
        $completionCondition3 = $this->prophesize(CompletionCondition::class);

        $manager->getForModule('module1')->willReturn(['class1', 'class2', 'class3']);
        $factory->createCompletionConditionFromClassName('class1', 'module1')->willReturn($completionCondition1->reveal());
        $factory->createCompletionConditionFromClassName('class2', 'module1')->willReturn($completionCondition2->reveal());
        $factory->createCompletionConditionFromClassName('class3', 'module1')->willReturn($completionCondition3->reveal());
        $repository = new CompletionConditionRepository($manager->reveal(), $factory->reveal());

        $this->assertEquals([
            $completionCondition1->reveal(),
            $completionCondition2->reveal(),
            $completionCondition3->reveal()
        ], $repository->getAllForModule('module1'));
    }
}

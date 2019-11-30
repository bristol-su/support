<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionFactory;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Container\Container;

class CompletionConditionFactoryTest extends TestCase
{

    /** @test */
    public function createCompletionConditionFromClassName_resolves_a_completion_condition_from_the_container(){
        $container = $this->prophesize(Container::class);
        $condition = $this->prophesize(CompletionCondition::class);
        $container->make('ClassName', ['moduleAlias' => 'moduleAlias1'])->shouldBeCalled()->willReturn($condition->reveal());
        
        $factory = new CompletionConditionFactory($container->reveal());
        $this->assertEquals($condition->reveal(), 
            $factory->createCompletionConditionFromClassName('ClassName', 'moduleAlias1')
        );
    }
    
}
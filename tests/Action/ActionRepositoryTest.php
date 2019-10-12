<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionRepository;
use BristolSU\Support\Action\Contracts\ActionManager;
use BristolSU\Support\Action\Contracts\RegisteredAction;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Collection;

class ActionRepositoryTest extends TestCase
{

    /** @test */
    public function all_returns_all_actions_as_registered_actions()
    {
        $manager = $this->prophesize(ActionManager::class);
        $manager->all()->shouldBeCalled()->willReturn([
            'Class\One' => [
                'name' => 'Name1',
                'description' => 'Description1',
                'class' => 'Class\One'
            ],
            'Class\Two' => [
                'name' => 'Name2',
                'description' => 'Description2',
                'class' => 'Class\Two'
            ]
        ]);
        
        $repository = new ActionRepository($manager->reveal());
        $registeredActions = $repository->all();
        $this->assertInstanceOf(Collection::class, $registeredActions);
        $this->assertCount(2, $registeredActions);
        $this->assertEquals('Class\One', $registeredActions[0]->getClassName());
        $this->assertEquals('Name1', $registeredActions[0]->getName());
        $this->assertEquals('Class\Two', $registeredActions[1]->getClassName());
        $this->assertEquals('Name2', $registeredActions[1]->getName());
    }

    /** @test */
    public function fromClass_returns_a_single_action_as_a_registered_action()
    {
        $manager = $this->prophesize(ActionManager::class);
        $manager->fromClass('Class\One')->shouldBeCalled()->willReturn([
            'name' => 'Name1',
            'description' => 'Description1',
            'class' => 'Class\One'
        ]);

        $repository = new ActionRepository($manager->reveal());
        $registeredAction = $repository->fromClass('Class\One');
        $this->assertInstanceOf(RegisteredAction::class, $registeredAction);
        $this->assertEquals('Class\One', $registeredAction->getClassName());
        $this->assertEquals('Name1', $registeredAction->getName());
        $this->assertEquals('Description1', $registeredAction->getDescription());
    }

    /** @test */
    public function fromClass_throws_an_exception_if_action_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Action [Class\One] not found');
        
        $manager = $this->prophesize(ActionManager::class);
        $manager->fromClass('Class\One')->shouldBeCalled()->willThrow(new \Exception('Action [Class\One] not found'));

        $repository = new ActionRepository($manager->reveal());
        $registeredAction = $repository->fromClass('Class\One');
    }
}
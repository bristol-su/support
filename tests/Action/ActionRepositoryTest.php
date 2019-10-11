<?php


namespace BristolSU\Support\Tests\Action;


use BristolSU\Support\Action\ActionRepository;
use BristolSU\Support\Action\Contracts\ActionManager;
use BristolSU\Support\Tests\TestCase;

class ActionRepositoryTest extends TestCase
{

    /** @test */
    public function it_retrieves_all_completion_events_from_config(){
        $manager = $this->prophesize(ActionManager::class);
        $manager->allForModule('alias1')->shouldBeCalled()->willReturn([['name' => 'Completion1']]);

        $completions = (new ActionRepository($manager->reveal()))->allForModule('alias1');
        $this->assertEquals('Completion1', $completions[0]['name']);
    }

}

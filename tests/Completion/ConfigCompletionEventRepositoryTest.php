<?php


namespace BristolSU\Support\Tests\Completion;


use BristolSU\Support\Completion\CompletionEventRepository;
use BristolSU\Support\Completion\Contracts\CompletionEventManager;
use BristolSU\Support\Testing\TestCase;

class ConfigCompletionEventRepositoryTest extends TestCase
{

    /** @test */
    public function it_retrieves_all_completion_events_from_config(){
        $manager = $this->prophesize(CompletionEventManager::class);
        $manager->allForModule('alias1')->shouldBeCalled()->willReturn([['name' => 'Completion1']]);

        $completions = (new CompletionEventRepository($manager->reveal()))->allForModule('alias1');
        $this->assertEquals('Completion1', $completions[0]['name']);
    }

}

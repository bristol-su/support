<?php


namespace BristolSU\Support\Tests\Integration\Completion;


use BristolSU\Support\Completion\ConfigCompletionEventRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\User\User;
use Illuminate\Config\Repository;
use BristolSU\Support\Testing\TestCase;

class ConfigCompletionEventRepositoryTest extends TestCase
{

    /** @test */
    public function it_retrieves_all_completion_events_from_config(){
        $config =$this->prophesize(Repository::class);
        $config->get('alias1.completion')->shouldBeCalled()->willReturn([['name' => 'Completion1']]);

        $completions = (new ConfigCompletionEventRepository($config->reveal()))->allForModule('alias1');
        $this->assertEquals('Completion1', $completions[0]['name']);
    }

}

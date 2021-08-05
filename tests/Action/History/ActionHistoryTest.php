<?php

namespace BristolSU\Support\Tests\Action\History;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\History\ActionHistory;
use BristolSU\Support\Tests\TestCase;

class ActionHistoryTest extends TestCase
{
    /** @test */
    public function an_action_history_can_be_created()
    {
        $actionInstance = ActionInstance::factory()->create();

        $actionHistory = ActionHistory::factory()->create([
            'action_instance_id' => $actionInstance->id,
            'message' => 'Message 1',
            'success' => 1
        ]);

        $this->assertInstanceOf(ActionHistory::class, $actionHistory);
        $this->assertDatabaseHas('action_histories', [
            'id' => $actionHistory->id,
            'action_instance_id' => $actionInstance->id,
            'message' => 'Message 1',
            'success' => 1
        ]);
    }

    /** @test */
    public function it_has_an_action_instance()
    {
        $actionInstance = ActionInstance::factory()->create();

        $actionHistory = ActionHistory::factory()->create([
            'action_instance_id' => $actionInstance->id,
        ]);

        $this->assertInstanceOf(ActionInstance::class, $actionHistory->actionInstance);
        $this->assertModelEquals($actionInstance, $actionHistory->actionInstance);
    }
}

<?php

namespace BristolSU\Support\Tests\Action\History;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class HasHistoryTest extends TestCase
{
    /** @test */
    public function an_exception_is_thrown_if_the_action_instance_is_null()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The action instance ID must not be null');
        
        $action = new ActionForResponse();
        $action->response = ActionResponse::success();
        
        $action->handle();
    }
    
    /** @test */
    public function if_the_settings_are_null_an_empty_array_is_saved()
    {
        $action = new ActionForResponse();
        $action->response = ActionResponse::success('Message 1');
        $action->setActionInstanceId(1);
        $action->setEventFields(['a' => 'b']);

        $action->handle();
        $this->assertDatabaseHas('action_histories', [
            'event_fields' => json_encode(['a' => 'b']),
            'settings' => json_encode([]),
            'message' => 'Message 1',
            'success' => 1
        ]);
    }
    
    /** @test */
    public function if_event_fields_are_null_an_empty_array_is_saved()
    {
        $action = new ActionForResponse();
        $action->response = ActionResponse::success('Message 1');
        $action->setActionInstanceId(1);
        $action->setSettings(['a' => 'b']);

        $action->handle();
        $this->assertDatabaseHas('action_histories', [
            'settings' => json_encode(['a' => 'b']),
            'event_fields' => json_encode([]),
            'message' => 'Message 1',
            'success' => 1
        ]);
    }
    
    /** @test */
    public function action_history_is_created_from_the_response()
    {
        $action = new ActionForResponse();
        $action->response = ActionResponse::success('Message 1');
        $action->setActionInstanceId(1);
        $action->setSettings(['a' => 'b']);
        $action->setEventFields(['c' => 'd']);

        $action->handle();
        $this->assertDatabaseHas('action_histories', [
            'settings' => json_encode(['a' => 'b']),
            'event_fields' => json_encode(['c' => 'd']),
            'action_instance_id' => 1,
            'message' => 'Message 1',
            'success' => 1
        ]);
    }
}

class ActionForResponse extends Action
{
    public $response;
    
    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        // TODO: Implement options() method.
    }

    public function run(): ActionResponse
    {
        return $this->response;
    }
}

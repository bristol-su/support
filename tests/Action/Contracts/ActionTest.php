<?php

namespace BristolSU\Support\Tests\Action\Contracts;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Tests\TestCase;
use Exception;
use FormSchema\Schema\Form;

class ActionTest extends TestCase
{

    /** @test */
    public function option_returns_an_index_given_by_data()
    {
        $action = new DummyAction(['key' => 'value']);
        $this->assertEquals('value', $action->option('key'));
    }

    /** @test */
    public function option_returns_the_default_if_no_key_found()
    {
        $action = new DummyAction([]);
        $this->assertEquals('default', $action->option('key', 'default'));
    }

    /** @test */
    public function run_is_called()
    {
        $action = new DummyAction([]);
        $this->assertFalse($action->ran);
        $action->setActionInstanceId(1);
        $action->handle();
        $this->assertTrue($action->ran);
    }

    /** @test */
    public function the_response_from_run_is_accessible()
    {
        $action = new DummyAction([]);
        $action->setActionInstanceId(1);
        $response = ActionResponse::success('A success');
        $action->responseToReturn = $response;

        $this->assertNull($action->getResponse());

        $action->handle();

        $this->assertSame($response, $action->getResponse());
    }

    /** @test */
    public function getData_returns_all_data()
    {
        $action = new DummyAction(['key1' => 'val1', 'key2' => 'val2']);
        $this->assertEquals(['key1' => 'val1', 'key2' => 'val2'], $action->getData());
    }

    /** @test */
    public function handle_creates_a_failed_response_with_the_exception_message_if_exception_thrown_in_run()
    {
        $action = new DummyActionWithException([]);
        $action->setActionInstanceId(1);
        $action->exceptionMessage = 'An exception message';
        $action->handle();

        $response = $action->getResponse();
        $this->assertEquals('An exception message', $response->getMessage());
        $this->assertFalse($response->getSuccess());
    }

    /** @test */
    public function handle_creates_a_failed_response_with_default_exception_message_if_exception_with_no_message_thrown_in_run()
    {
        $action = new DummyActionWithException([]);
        $action->setActionInstanceId(1);
        $action->handle();

        $response = $action->getResponse();
        $this->assertEquals('An error was thrown during processing', $response->getMessage());
        $this->assertFalse($response->getSuccess());
    }

    /** @test */
    public function it_creates_a_history_when_the_response_is_returned(){
        $action = new DummyAction([]);
        $action->setActionInstanceId(1);
        $response = ActionResponse::success('Message Here');
        $action->responseToReturn = $response;

        $action->handle();

        $this->assertDatabaseHas('action_histories', [
            'action_instance_id' => 1,
            'success' => 1,
            'message' => 'Message Here'
        ]);
    }

}

class DummyAction extends Action
{

    public $ran = false;

    public $responseToReturn;

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        // TODO: Implement options() method.
    }

    public function run(): ActionResponse
    {
        $this->ran = true;
        return ($this->responseToReturn ?? ActionResponse::success());
    }
}

class DummyActionWithException extends Action
{

    public $exceptionMessage = '';

    public static function options(): Form
    {
        // TODO: Implement options() method.
    }

    public function run(): ActionResponse
    {
        throw new Exception($this->exceptionMessage);
    }
}

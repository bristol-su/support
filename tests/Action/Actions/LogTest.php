<?php

namespace BristolSU\Support\Tests\Action\Actions;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Psr\Log\LoggerInterface;

class LogTest extends TestCase
{
    /** @test */
    public function handle_logs_the_given_text()
    {
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled();
        $this->instance('log', $logFake->reveal());
        
        $log = new Log(['text' => 'Log Me']);
        $log->setActionInstanceId(1);
        $log->handle();
    }

    /** @test */
    public function options_returns_a_form_schema()
    {
        $this->assertInstanceOf(Form::class, Log::options());
    }
    
    /** @test */
    public function it_returns_a_failed_response_if_logging_threw_exception()
    {
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled()->willThrow(new \Exception('An exception message'));
        $this->instance('log', $logFake->reveal());

        $log = new Log(['text' => 'Log Me']);
        $log->setActionInstanceId(1);
        $response = $log->handle();
        $this->assertInstanceOf(ActionResponse::class, $log->getResponse());
        $this->assertEquals('An exception message', $log->getResponse()->getMessage());
        $this->assertEquals(false, $log->getResponse()->getSuccess());
    }

    /** @test */
    public function it_has_a_default_error_if_the_exception_has_no_error_message()
    {
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled()->willThrow(new \Exception());
        $this->instance('log', $logFake->reveal());

        $log = new Log(['text' => 'Log Me']);
        $log->setActionInstanceId(1);
        $response = $log->handle();
        $this->assertInstanceOf(ActionResponse::class, $log->getResponse());
        $this->assertEquals('Could not log the text', $log->getResponse()->getMessage());
        $this->assertEquals(false, $log->getResponse()->getSuccess());
    }

    /** @test */
    public function it_returns_a_success_response_if_logging_successful()
    {
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled();
        $this->instance('log', $logFake->reveal());

        $log = new Log(['text' => 'Log Me']);
        $log->setActionInstanceId(1);
        $response = $log->handle();
        $this->assertInstanceOf(ActionResponse::class, $log->getResponse());
        $this->assertEquals('Text saved to the log file', $log->getResponse()->getMessage());
        $this->assertEquals(true, $log->getResponse()->getSuccess());
    }
}

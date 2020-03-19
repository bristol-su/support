<?php

namespace BristolSU\Support\Tests\Action\Actions;

use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Psr\Log\LoggerInterface;

class LogTest extends TestCase
{

    /** @test */
    public function handle_logs_the_given_text(){
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled();
        $this->instance('log', $logFake->reveal());
        
        $log = new Log(['text' => 'Log Me']);
        $log->handle();
    }

    /** @test */
    public function options_returns_a_form_schema()
    {
        $this->assertInstanceOf(Form::class, Log::options());
    }
    
}

<?php

namespace BristolSU\Support\Tests\Action\Actions;

use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Tests\TestCase;
use Psr\Log\LoggerInterface;

class LogTest extends TestCase
{

    /** @test */
    public function getFields_returns_all_fields(){
        $log = new Log(['text' => '']);
        $this->assertArrayHasKey('text', $log->getFields());
    }
    
    /** @test */
    public function getFieldMetaData_returns_field_metadata(){
        $this->assertArrayHasKey('text', Log::getFieldMetaData());
    }
    
    /** @test */
    public function data_passed_into_the_action_is_initialised_as_fields(){
        $log = new Log(['text' => 'testtext']);
        $this->assertArrayHasKey('text', $log->getFields());
        $this->assertEquals('testtext', $log->getFields()['text']);
    }
    
    /** @test */
    public function handle_logs_the_given_text(){
        $logFake = $this->prophesize(LoggerInterface::class);
        $logFake->info('Log Me')->shouldBeCalled();
        $this->instance('log', $logFake->reveal());
        
        $log = new Log(['text' => 'Log Me']);
        $log->handle();
    }
    
}
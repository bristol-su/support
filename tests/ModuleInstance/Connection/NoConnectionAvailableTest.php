<?php

namespace BristolSU\Support\Tests\ModuleInstance\Connection;

use BristolSU\Support\ModuleInstance\Connection\NoConnectionAvailable;
use BristolSU\Support\Tests\TestCase;

class NoConnectionAvailableTest extends TestCase
{

    /** @test */
    public function it_defaults_the_message_to_a_connection_failed_message(){
        $exception = new NoConnectionAvailable();
        $this->assertEquals('No connection has been found', $exception->getMessage());
    }
    
}
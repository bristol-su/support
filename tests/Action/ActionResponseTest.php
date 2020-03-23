<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Tests\TestCase;

class ActionResponseTest extends TestCase
{

    /** @test */
    public function a_message_can_be_set_and_got(){
        $response = new ActionResponse();
        $response->setMessage('Message 1');
        $this->assertEquals('Message 1', $response->getMessage());
    }
    
    /** @test */
    public function a_success_can_be_set_and_got(){
        $response = new ActionResponse();
        $response->setSuccess(true);
        $this->assertTrue($response->getSuccess());
    }
    
    /** @test */
    public function the_static_factory_can_create_a_success_response(){
        $response = ActionResponse::success('Message 1');
        $this->assertTrue($response->getSuccess());
        $this->assertEquals('Message 1', $response->getMessage());
    }
    
    /** @test */
    public function the_static_factory_can_create_a_failed_response(){
        $response = ActionResponse::failure('Message 1');
        $this->assertFalse($response->getSuccess());
        $this->assertEquals('Message 1', $response->getMessage());
    }
    
}
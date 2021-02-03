<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Authorization\Exception\IncorrectLogin;
use BristolSU\Support\Tests\TestCase;

class IncorrectLoginTest extends TestCase
{
    /** @test */
    public function it_has_a_code_of_403()
    {
        $e = new IncorrectLogin();
        
        $this->assertEquals(403, $e->getStatusCode());
    }
    
    /** @test */
    public function it_sets_a_message()
    {
        $e = new IncorrectLogin('Some Message');

        $this->assertEquals('Some Message', $e->getMessage());
    }
}

<?php

namespace BristolSU\Support\Tests\Authentication\Exception;

use BristolSU\Support\Authentication\Exception\IsAuthenticatedException;
use BristolSU\Support\Tests\TestCase;

class IsAuthenticatedExceptionTest extends TestCase
{
    /** @test */
    public function it_has_a_default_message_and_code()
    {
        $exception = new IsAuthenticatedException();

        $this->assertEquals('You must be unauthenticated.', $exception->getMessage());
        $this->assertEquals(403, $exception->getCode());
    }
}

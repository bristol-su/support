<?php

namespace BristolSU\Support\Tests\Authentication\Exception;

use BristolSU\Support\Authentication\Exception\PasswordUnconfirmed;
use BristolSU\Support\Tests\TestCase;

class PasswordUnconfirmedTest extends TestCase
{
    /** @test */
    public function it_has_a_default_message_and_code()
    {
        $exception = new PasswordUnconfirmed();

        $this->assertEquals('Password confirmation required', $exception->getMessage());
        $this->assertEquals(423, $exception->getCode());
    }
}

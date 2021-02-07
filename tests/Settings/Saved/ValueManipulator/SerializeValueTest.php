<?php

namespace BristolSU\Support\Tests\Settings\Saved\ValueManipulator;

use BristolSU\Support\Settings\Saved\ValueManipulator\SerializeValue;
use BristolSU\Support\Tests\TestCase;

class SerializeValueTest extends TestCase
{
    public function assertCanHandle(mixed $val)
    {
        $manipulator = new SerializeValue();
        $encoded = $manipulator->encode('key1', $val);
        $this->assertIsString($encoded);

        $decoded = $manipulator->decode('key1', $encoded);
        $this->assertEquals($val, $decoded);
    }

    /** @test */
    public function it_can_handle_a_string()
    {
        $this->assertCanHandle('val1');
        $this->assertCanHandle('A much longer value with spaces, punctuation!>)"E_Q) and ' . PHP_EOL . 'even a new line!');
    }

    /** @test */
    public function it_can_handle_a_integer()
    {
        $this->assertCanHandle(1);
        $this->assertCanHandle(0);
        $this->assertCanHandle(500);
    }

    /** @test */
    public function it_can_handle_an_array()
    {
        $this->assertCanHandle([]);
        $this->assertCanHandle(['test1']);
        $this->assertCanHandle(['test1', 'yellow']);
        $this->assertCanHandle(['test1', 'yellow', 333]);
        $this->assertCanHandle([['another' => 'array'], ['and' => 'another']]);
        $this->assertCanHandle(['test1', 'yellow', ['another' => 'array'], 333]);
    }

    /** @test */
    public function it_can_handle_a_boolean()
    {
        $this->assertCanHandle(true);
        $this->assertCanHandle(false);
    }

    /** @test */
    public function it_can_handle_a_float()
    {
        $this->assertCanHandle(0.0158);
        $this->assertCanHandle(0.22222);
        $this->assertCanHandle(18392389.2225325346457);
    }

    /** @test */
    public function it_can_handle_a_null_value()
    {
        $this->assertCanHandle(null);
    }

    /** @test */
    public function it_can_handle_a_serializable_object()
    {
        $object = new TestSerializableClass();

        $manipulator = new SerializeValue();
        $encoded = $manipulator->encode('key1', $object);
        $this->assertIsString($encoded);

        $decoded = $manipulator->decode('key1', $encoded);
        $this->assertInstanceOf(TestSerializableClass::class, $decoded);
        $this->assertEquals('new', $decoded->test);
    }
}

class TestSerializableClass
{
    public $test = 'no';

    public function __sleep()
    {
        return ['test'];
    }

    public function __wakeup()
    {
        $this->test = 'new';
    }
}

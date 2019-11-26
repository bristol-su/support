<?php

namespace BristolSU\Support\Tests;

use BristolSU\Support\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    // TODO remove dependency of the testcase, just extend orchestra directly
    public function alias(): string
    {
        return 'support';
    }
}
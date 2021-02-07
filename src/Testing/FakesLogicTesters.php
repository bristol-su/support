<?php

namespace BristolSU\Support\Testing;

use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Testing\LogicTester\LogicTesterFake;

/**
 * Trait with tools for creating fake logic testers for testing purposes.
 */
trait FakesLogicTesters
{
    /**
     * Holds the fake logic tester.
     *
     * @var LogicTester|LogicTesterFake
     */
    private $logicTester;

    /**
     * Get or create a new faked logic tester.
     *
     * @return LogicTester|LogicTesterFake
     */
    public function logicTester()
    {
        if ($this->logicTester === null) {
            $this->logicTester = new LogicTesterFake();
        }

        return $this->logicTester;
    }
}

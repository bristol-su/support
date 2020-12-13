<?php

namespace BristolSU\Support\Tests;

use BristolSU\Support\Testing\AssertsEloquentModels;
use BristolSU\Support\Testing\FakesLogicTesters;
use BristolSU\Support\Testing\HandlesAuthentication;
use BristolSU\Support\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Prophecy\PhpUnit\ProphecyTrait;

class TestCase extends BaseTestCase
{
    use DatabaseTransactions, HandlesAuthentication, FakesLogicTesters, AssertsEloquentModels, ProphecyTrait;

}

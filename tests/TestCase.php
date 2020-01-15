<?php

namespace BristolSU\Support\Tests;

use BristolSU\Support\Testing\AssertsEloquentModels;
use BristolSU\Support\Testing\FakesLogicTesters;
use BristolSU\Support\Testing\HandlesAuthentication;
use BristolSU\Support\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends BaseTestCase
{
    use DatabaseMigrations, HandlesAuthentication, FakesLogicTesters, AssertsEloquentModels;
    
}
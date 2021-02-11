<?php

namespace BristolSU\Support\Testing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Assert;

/**
 * Trait to aid asserting features of Eloquent models.
 */
trait AssertsEloquentModels
{
    /**
     * Assert two Eloquent models are equal.
     *
     * @param Model $expected Expected model
     * @param Model $actual Actual model to test
     */
    public function assertModelEquals(Model $expected, Model $actual)
    {
        Assert::assertTrue($expected->is($actual), 'Models are not equal');
    }
}

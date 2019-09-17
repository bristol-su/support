<?php

namespace BristolSU\Support\Tests\Testing;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Testing\AssertsEloquentModels;
use BristolSU\Support\Tests\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

class AssertsEloquentModelsTest extends TestCase
{
    use AssertsEloquentModels;
    
    /** @test */
    public function it_asserts_identical_models_are_the_same(){
        $model = factory(Activity::class)->create();
        
        $this->assertModelEquals($model, $model);
    }

    /** @test */
    public function it_asserts_two_different_models_are_different(){
        $this->expectException(ExpectationFailedException::class);

        $model1 = factory(Activity::class)->create();
        $model2 = factory(Activity::class)->create();
        
        $this->assertModelEquals($model1, $model2);
    }

}
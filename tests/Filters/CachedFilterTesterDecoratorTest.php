<?php

namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\CachedFilterTesterDecorator;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Cache\Repository as Cache;
use Prophecy\Argument;

class CachedFilterTesterDecoratorTest extends TestCase
{

    /** @test */
    public function it_caches_and_returns_the_result_of_evaluate(){
        $filterInstance = factory(FilterInstance::class)->create();
        $model = $this->newUser();

        $realTester = $this->prophesize(FilterTester::class);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $filterInstance->id === $arg->id;
        }), Argument::that(function($arg) use ($model) {
            return $model->id === $arg->id;
        }))->shouldBeCalledTimes(1)->willReturn(true);

        $tester = new CachedFilterTesterDecorator($realTester->reveal(), app(Cache::class));
        $this->assertTrue($tester->evaluate($filterInstance, $model));
        $this->assertTrue($tester->evaluate($filterInstance, $model));
        $this->assertTrue($tester->evaluate($filterInstance, $model));
        $this->assertTrue($tester->evaluate($filterInstance, $model));
    }

    /** @test */
    public function the_cache_key_changes_if_filter_instance_changes(){
        $filterInstance1 = factory(FilterInstance::class)->create();
        $filterInstance2 = factory(FilterInstance::class)->create();
        $model = $this->newUser();

        $realTester = $this->prophesize(FilterTester::class);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance1) {
            return $filterInstance1->id === $arg->id;
        }), Argument::that(function($arg) use ($model) {
            return $model->id === $arg->id;
        }))->shouldBeCalledTimes(1)->willReturn(true);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance2) {
            return $filterInstance2->id === $arg->id;
        }), Argument::that(function($arg) use ($model) {
            return $model->id === $arg->id;
        }))->shouldBeCalledTimes(1)->willReturn(false);

        $tester = new CachedFilterTesterDecorator($realTester->reveal(), app(Cache::class));
        $this->assertTrue($tester->evaluate($filterInstance1, $model));
        $this->assertFalse($tester->evaluate($filterInstance2, $model));
        $this->assertTrue($tester->evaluate($filterInstance1, $model));
        $this->assertFalse($tester->evaluate($filterInstance2, $model));
    }

    /** @test */
    public function the_cache_key_changes_if_model_id_changes(){
        $filterInstance = factory(FilterInstance::class)->create();
        $model1 = $this->newUser();
        $model2 = $this->newUser();

        $realTester = $this->prophesize(FilterTester::class);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $filterInstance->id === $arg->id;
        }), Argument::that(function($arg) use ($model1) {
            return $model1->id === $arg->id;
        }))->shouldBeCalledTimes(1)->willReturn(false);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $filterInstance->id === $arg->id;
        }), Argument::that(function($arg) use ($model2) {
            return $model2->id === $arg->id;
        }))->shouldBeCalledTimes(1)->willReturn(true);

        $tester = new CachedFilterTesterDecorator($realTester->reveal(), app(Cache::class));
        $this->assertFalse($tester->evaluate($filterInstance, $model1));
        $this->assertTrue($tester->evaluate($filterInstance, $model2));
        $this->assertFalse($tester->evaluate($filterInstance, $model1));
        $this->assertTrue($tester->evaluate($filterInstance, $model2));
    }

    /** @test */
    public function the_cache_key_changes_if_model_type_changes(){
        $filterInstance = factory(FilterInstance::class)->create();
        $model1 = $this->newUser();
        $model2 = $this->newGroup();

        $realTester = $this->prophesize(FilterTester::class);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $filterInstance->id === $arg->id;
        }), Argument::that(function($arg) use ($model1) {
            return get_class($model1) === get_class($arg);
        }))->shouldBeCalledTimes(1)->willReturn(false);
        $realTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $filterInstance->id === $arg->id;
        }), Argument::that(function($arg) use ($model2) {
            return get_class($model2) === get_class($arg);
        }))->shouldBeCalledTimes(1)->willReturn(true);

        $tester = new CachedFilterTesterDecorator($realTester->reveal(), app(Cache::class));
        $this->assertFalse($tester->evaluate($filterInstance, $model1));
        $this->assertTrue($tester->evaluate($filterInstance, $model2));
        $this->assertFalse($tester->evaluate($filterInstance, $model1));
        $this->assertTrue($tester->evaluate($filterInstance, $model2));
    }

}

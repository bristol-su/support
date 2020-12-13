<?php

namespace BristolSU\Support\Tests\Filters\Jobs;

use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\Jobs\CacheFilter;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CacheFilterTest extends TestCase
{

    /** @test */
    public function it_evaluates_the_given_filter_with_the_given_model(){
        $model = $this->newUser();
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'alias1',
            'settings' => ['key1' => 'val1', 'key2' => 'val2']
        ]);

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate(Argument::that(function($arg) use ($filterInstance) {
            return $arg instanceof FilterInstance && $filterInstance->id === $arg->id;
        }), $model)->shouldBeCalled()->willReturn(true);

        $job = new CacheFilter($filterInstance, $model);
        $job->handle($filterTester->reveal());

    }

}

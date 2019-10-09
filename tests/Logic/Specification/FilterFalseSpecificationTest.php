<?php

namespace BristolSU\Support\Tests\Logic\Specification;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Tests\TestCase;

class FilterFalseSpecificationTest extends TestCase
{

    /** @test */
    public function it_returns_true_if_the_filter_is_false(){
        $filter = $this->prophesize(FilterInstance::class);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal())->shouldBeCalled()->willReturn(false);

        $spec = new FilterFalseSpecification($filter->reveal(), $filterTester->reveal());

        $this->assertTrue(
            $spec->isSatisfied()
        );
    }

    /** @test */
    public function it_returns_false_if_the_filter_is_true(){
        $filter = $this->prophesize(FilterInstance::class);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal())->shouldBeCalled()->willReturn(true);

        $spec = new FilterFalseSpecification($filter->reveal(), $filterTester->reveal());

        $this->assertFalse(
            $spec->isSatisfied()
        );
    }

}

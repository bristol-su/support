<?php

namespace BristolSU\Support\Tests\Logic\Specification;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Logic\Specification\NotInFilterAudienceSpecification;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class NotInFilterAudienceSpecificationTest extends TestCase
{

    /** @test */
    public function it_gets_the_filter_by_alias(){
        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias1');
        $filterInstance->settings()->shouldBeCalled()->willReturn([]);

        $filter = $this->prophesize(Filter::class);
        $filter->audience([])->shouldBeCalled()->willReturn([]);

        $filterRepository = $this->prophesize(FilterRepository::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $item = new class {};

        $specification = new NotInFilterAudienceSpecification($item, $filterInstance->reveal(), $filterRepository->reveal());
        $specification->isSatisfied();
    }

    /** @test */
    public function it_returns_false_if_the_item_id_is_in_the_filter_audience_id(){
        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias1');
        $filterInstance->settings()->shouldBeCalled()->willReturn([]);

        $notItem = new class {
            public $id = 2;
        };

        $item = new class {
            public $id = 1;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->audience([])->shouldBeCalled()->willReturn([$notItem, $item]);

        $filterRepository = $this->prophesize(FilterRepository::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());


        $specification = new NotInFilterAudienceSpecification($item, $filterInstance->reveal(), $filterRepository->reveal());
        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function it_returns_true_if_the_item_id_is_not_in_the_filter_audience_id(){
        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias1');
        $filterInstance->settings()->shouldBeCalled()->willReturn([]);

        $item = new class {
            public $id = 1;
        };

        $notItem = new class {
            public $id = 2;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->audience([])->shouldBeCalled()->willReturn([$notItem]);

        $filterRepository = $this->prophesize(FilterRepository::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());


        $specification = new NotInFilterAudienceSpecification($item, $filterInstance->reveal(), $filterRepository->reveal());
        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

}

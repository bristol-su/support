<?php


namespace BristolSU\Support\Tests\Unit\Filters;


use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\FilterFactory;
use Illuminate\Contracts\Container\Container;
use BristolSU\Support\Testing\TestCase;

class FilterFactoryTest extends TestCase
{

    /** @test */
    public function it_creates_a_filter_from_the_container(){
        $container = $this->prophesize(Container::class);
        $filter = $this->prophesize(Filter::class);
        $container->make('ClassName')->shouldBeCalled()->willReturn($filter->reveal());

        $filterFactory = new FilterFactory($container->reveal());
        $createdFilter = $filterFactory->createFilterFromClassName('ClassName');

        $this->assertInstanceOf(Filter::class, $createdFilter);
        $this->assertEquals($filter->reveal(), $createdFilter);
    }

}

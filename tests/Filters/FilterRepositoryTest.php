<?php


namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\Contracts\FilterFactory;
use BristolSU\Support\Filters\Contracts\FilterManager;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\FilterRepository;
use BristolSU\Support\Tests\TestCase;

class FilterRepositoryTest extends TestCase
{
    /** @test */
    public function get_by_alias_gets_a_filter_by_alias()
    {
        $manager = $this->prophesize(FilterManager::class);
        $manager->getClassFromAlias('alias')->shouldBeCalled()->willReturn('FilterClassName');

        $filter = $this->prophesize(Filter::class);
        $factory = $this->prophesize(FilterFactory::class);
        $factory->createFilterFromClassName('FilterClassName')->shouldBeCalled()->willReturn($filter->reveal());

        $repository = new FilterRepository($manager->reveal(), $factory->reveal());
        $this->assertEquals($filter->reveal(), $repository->getByAlias('alias'));
    }

    /** @test */
    public function get_by_alias_throws_an_exception_if_filter_not_found()
    {
        $this->expectException(\Exception::class);

        $manager = $this->prophesize(FilterManager::class);
        $manager->getClassFromAlias('nonexistent')->shouldBeCalled()->willThrow(new \Exception());
        $factory = $this->prophesize(FilterFactory::class);

        $repository = new FilterRepository($manager->reveal(), $factory->reveal());
        $repository->getByAlias('nonexistent');
    }

    /** @test */
    public function get_all_returns_all_filters_by_alias()
    {
        $filterAlias = [
            'Class1',
            'Class2',
            'Class3',
            'Class4'
        ];

        $manager = $this->prophesize(FilterManager::class);
        $manager->getAll()->shouldBeCalled()->willReturn($filterAlias);

        $factory = $this->prophesize(FilterFactory::class);
        foreach ($filterAlias as $alias) {
            $factory->createFilterFromClassName($alias)->shouldBeCalled()->willReturn($alias);
        }

        $repository = new FilterRepository($manager->reveal(), $factory->reveal());
        $this->assertEquals($filterAlias, $repository->getAll());
    }

    /** @test */
    public function get_all_returns_an_empty_array_if_no_filters_found()
    {
        $manager = $this->prophesize(FilterManager::class);
        $manager->getAll()->shouldBeCalled()->willReturn([]);

        $factory = $this->prophesize(FilterFactory::class);

        $repository = new FilterRepository($manager->reveal(), $factory->reveal());
        $this->assertEquals([], $repository->getAll());
    }
}

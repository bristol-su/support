<?php


namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterTester;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class FilterTesterTest extends TestCase
{
    /** @test */
    public function evaluate_gets_a_filter_by_alias()
    {
        $filter = $this->prophesize(Filter::class);
        $filter->setModel(Argument::any())->willReturn(null);
        $filter->evaluate(Argument::any())->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'alias1']);

        $filterTester = new FilterTester($repository->reveal());
        $filterTester->evaluate($filterInstance, new class() {
        });
    }

    /** @test */
    public function evaluate_sets_the_model()
    {
        $user = new class() {
            public $id = 1;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->setModel($user)->shouldBeCalled()->willReturn(null);
        $filter->evaluate(Argument::any())->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'alias1']);

        $filterTester = new FilterTester($repository->reveal());
        $filterTester->evaluate($filterInstance, $user);
    }
    
    /** @test */
    public function evaluate_passes_the_settings_to_the_filter()
    {
        $user = new class() {
            public $id = 1;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->setModel($user)->willReturn(null);
        $filter->evaluate(['key1' => 'val1', 'key2' => 'val2'])->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'alias1',
            'settings' => ['key1' => 'val1', 'key2' => 'val2']
        ]);

        $filterTester = new FilterTester($repository->reveal());
        $filterTester->evaluate($filterInstance, $user);
    }

    /** @test */
    public function evaluate_returns_true_if_the_filter_is_true()
    {
        $user = new class() {
            public $id = 1;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->setModel($user)->willReturn(null);
        $filter->evaluate(['key1' => 'val1', 'key2' => 'val2'])->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'alias1',
            'settings' => ['key1' => 'val1', 'key2' => 'val2']
        ]);

        $filterTester = new FilterTester($repository->reveal());
        $this->assertTrue(
            $filterTester->evaluate($filterInstance, $user)
        );
    }

    /** @test */
    public function evaluate_returns_false_if_the_filter_is_false()
    {
        $user = new class() {
            public $id = 1;
        };

        $filter = $this->prophesize(Filter::class);
        $filter->setModel($user)->willReturn(null);
        $filter->evaluate(['key1' => 'val1', 'key2' => 'val2'])->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias1')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'alias1',
            'settings' => ['key1' => 'val1', 'key2' => 'val2']
        ]);

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse(
            $filterTester->evaluate($filterInstance, $user)
        );
    }
}

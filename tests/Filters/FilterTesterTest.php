<?php


namespace BristolSU\Support\Tests\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterTester;
use BristolSU\Support\Testing\TestCase;

class FilterTesterTest extends TestCase
{

    /** @test */
    public function it_evaluates_a_filter(){
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference1'])->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias');
        $filterInstance->settings()->shouldBeCalled()->willReturn(['tag' => 'reference1']);

        $filterTester = new FilterTester($repository->reveal());
        $this->assertTrue($filterTester->evaluate($filterInstance->reveal()));
    }

    /** @test */
    public function it_returns_false_if_the_filter_does_not_have_a_model(){
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias');

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse($filterTester->evaluate($filterInstance->reveal()));

    }
    
    /** @test */
    public function it_returns_true_if_the_filter_is_true(){

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);
        
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference'])->shouldBeCalled()->willReturn(true);
        
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());
        
        $filterTester = new FilterTester($repository->reveal());
        $this->assertTrue(
            $filterTester->evaluate($filterInstance)
        );
    }

    /** @test */
    public function it_returns_false_if_the_filter_is_false(){
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);

        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference'])->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse(
            $filterTester->evaluate($filterInstance)
        );
    }

    /** @test */
    public function it_returns_false_if_the_model_is_not_given(){
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);

        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse(
            $filterTester->evaluate($filterInstance)
        );
    }
}

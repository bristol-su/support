<?php


namespace BristolSU\Support\Tests\Integration\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterTester;
use BristolSU\Support\Testing\TestCase;

class FilterTesterTest extends TestCase
{

    /** @test */
    public function it_returns_true_if_the_filter_is_true(){

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);

        $group = $this->prophesize(Group::class);

        // To make this filter true, the group must be tagged!
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->willReturn($group->reveal());
        $this->instance(Authentication::class, $authentication->reveal());

        $groupTag = $this->prophesize(GroupTagModelContract::class);
        $groupTag->fullReference()->willReturn('reference');

        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $groupTagRepository->allThroughGroup($group->reveal())->willReturn([$groupTag]);
        $this->instance(GroupTagRepositoryContract::class, $groupTagRepository->reveal());

        $this->instance(GroupRepository::class, $this->prophesize(GroupRepository::class)->reveal());
        $filterTester = resolve(FilterTester::class);
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

        $group = $this->prophesize(Group::class);

        // To make this filter true, the group must be tagged!
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->willReturn($group->reveal());
        $this->instance(Authentication::class, $authentication->reveal());

        $groupTag = $this->prophesize(GroupTagModelContract::class);
        $groupTag->fullReference()->willReturn('reference');

        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $groupTagRepository->allThroughGroup($group->reveal())->willReturn([]);
        $this->instance(GroupTagRepositoryContract::class, $groupTagRepository->reveal());
        $this->instance(GroupRepository::class, $this->prophesize(GroupRepository::class)->reveal());

        $filterTester = resolve(FilterTester::class);
        $this->assertFalse(
            $filterTester->evaluate($filterInstance)
        );
    }

    // TODO Filters should be evaluated to false if model is null

}

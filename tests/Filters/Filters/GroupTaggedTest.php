<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\GroupTag;
use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Filters\GroupTagged;
use BristolSU\Support\Testing\TestCase;

class GroupTaggedTest extends TestCase
{

    /** @test */
    public function it_returns_a_list_of_possible_tags(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $authentication = $this->prophesize(Authentication::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->name()->shouldBeCalled()->willReturn('Name1');
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->name()->shouldBeCalled()->willReturn('Name2');
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->all()->shouldBeCalled()->willReturn([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]);

        $groupTagFilter = new GroupTagged($authentication->reveal(), $groupTagRepository->reveal(), $this->prophesize(GroupRepository::class)->reveal());

        $this->assertEquals([
            'tag' => [
                'reference.ref1' => 'Name1',
                'reference.ref2' => 'Name2'
            ]
            ], $groupTagFilter->options());
    }

    /** @test */
    public function it_evaluates_to_true_if_group_tagged(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $authentication = $this->prophesize(Authentication::class);

        $group = $this->prophesize(Group::class);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group->reveal());

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->allThroughGroup($group->reveal())->shouldBeCalled()->willReturn([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]);

        $groupTagFilter = new GroupTagged($authentication->reveal(), $groupTagRepository->reveal(), $this->prophesize(GroupRepository::class)->reveal());

        $this->assertTrue($groupTagFilter->evaluate(['tag' => 'reference.ref2']));
    }

    /** @test */
    public function it_evaluates_to_false_if_group_not_tagged(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $authentication = $this->prophesize(Authentication::class);

        $group = $this->prophesize(Group::class);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group->reveal());

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->allThroughGroup($group->reveal())->shouldBeCalled()->willReturn([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]);

        $groupTagFilter = new GroupTagged($authentication->reveal(), $groupTagRepository->reveal(), $this->prophesize(GroupRepository::class)->reveal());

        $this->assertFalse($groupTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function has_model_returns_true_if_get_group_not_null(){
        $auth = $this->prophesize(Authentication::class);
        $auth->getGroup()->shouldBeCalled()->willReturn(new \BristolSU\Support\Control\Models\Group);

        $groupTaggedFilter = new GroupTagged($auth->reveal(), $this->prophesize(GroupTagRepositoryContract::class)->reveal(), $this->prophesize(GroupRepository::class)->reveal());
        $this->assertTrue($groupTaggedFilter->hasModel());
    }


    /** @test */
    public function has_model_returns_false_if_get_group_null(){
        $auth = $this->prophesize(Authentication::class);
        $auth->getGroup()->shouldBeCalled()->willReturn(null);

        $groupTaggedFilter = new GroupTagged($auth->reveal(), $this->prophesize(GroupTagRepositoryContract::class)->reveal(), $this->prophesize(GroupRepository::class)->reveal());
        $this->assertFalse($groupTaggedFilter->hasModel());
    }

    /** @test */
    public function audience_returns_the_audience(){
        $groupTagRepo = $this->prophesize(GroupTagRepositoryContract::class);
        $groupRepo = $this->prophesize(\BristolSU\Support\Control\Contracts\Repositories\Group::class);
        $groupTag = $this->prophesize(GroupTag::class);

        $groupTagRepo->getTagByFullReference('full.reference')->shouldBeCalled()->willReturn($groupTag->reveal());
        $groupRepo->allWithTag($groupTag->reveal())->shouldBeCalled()->willReturn([
            new \BristolSU\Support\Control\Models\Group(['id' => 1]),
            new \BristolSU\Support\Control\Models\Group(['id' => 2])]
        );

        $filter = new GroupTagged(
            $this->prophesize(Authentication::class)->reveal(),
            $groupTagRepo->reveal(),
            $groupRepo->reveal()
        );
        $audience = $filter->audience(['tag' => 'full.reference']);
        $this->assertEquals(1, $audience[0]->id);
        $this->assertEquals(2, $audience[1]->id);
    }
}

<?php


namespace BristolSU\Support\Tests\Filters\Filters\Group;


use BristolSU\ControlDB\Contracts\Models\Tags\GroupTag as GroupTagModelContract;
use BristolSU\ControlDB\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\ControlDB\Models\Group;
use BristolSU\Support\Filters\Filters\Group\GroupTagged;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class GroupTaggedTest extends TestCase
{
    
    /** @test */
    public function options_returns_a_list_of_possible_tags(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->name()->shouldBeCalled()->willReturn('Name1');
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->name()->shouldBeCalled()->willReturn('Name2');
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->all()->shouldBeCalled()->willReturn(collect([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());

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

        $group = $this->prophesize(Group::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->allThroughGroup($group->reveal())->shouldBeCalled()->willReturn(collect([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($group->reveal());
        $this->assertTrue($groupTagFilter->evaluate(['tag' => 'reference.ref2']));
    }

    /** @test */
    public function it_evaluates_to_false_if_group_not_tagged(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);

        $group = $this->prophesize(Group::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $groupTagRepository->allThroughGroup($group->reveal())->shouldBeCalled()->willReturn(collect([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($group->reveal());

        $this->assertFalse($groupTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function evaluate_returns_false_if_the_group_tag_repository_throws_an_error(){
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $groupTagRepository->allThroughGroup(Argument::any())->shouldBeCalled()->willThrow(new \Exception());

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($this->newGroup());
        $this->assertFalse($groupTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function name_returns_a_string(){
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string(){
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->alias());
    }
}

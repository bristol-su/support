<?php


namespace BristolSU\Support\Tests\Filters\Filters\Group;

use BristolSU\ControlDB\Contracts\Models\Tags\GroupTag as GroupTagModelContract;
use BristolSU\ControlDB\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Tags\GroupTag;
use BristolSU\ControlDB\Models\Tags\GroupTagCategory;
use BristolSU\Support\Filters\Filters\Group\GroupTagged;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Fields\SelectField;

class GroupTaggedTest extends TestCase
{
    /** @test */
    public function options_returns_a_list_of_possible_tags()
    {
        $groupTagCategory1 = GroupTagCategory::factory()->create(['name' => 'category1Name', 'reference' => 'cat1']);
        $groupTagCategory2 = GroupTagCategory::factory()->create(['name' => 'category2Name', 'reference' => 'cat2']);

        $groupTag1 = GroupTag::factory()->create([
            'tag_category_id' => $groupTagCategory1->id(),
            'name' => 'Name1',
            'reference' => 'ref1'
        ]);

        $groupTag2 = GroupTag::factory()->create([
            'tag_category_id' => $groupTagCategory2->id(),
            'name' => 'Name2',
            'reference' => 'ref2'
        ]);

        $groupTag3 = GroupTag::factory()->create([
            'tag_category_id' => $groupTagCategory1->id(),
            'name' => 'Name3',
            'reference' => 'ref3'
        ]);

        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $groupTagRepository->all()->shouldBeCalled()->willReturn(collect([
            $groupTag1, $groupTag2, $groupTag3
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());

        $groups = $groupTagFilter->options()->groups();
        $this->assertCount(1, $groups);
        $fields = $groups[0]->fields();
        $this->assertCount(1, $fields);
        $field = $fields[0];

        $this->assertInstanceOf(SelectField::class, $field);
        $this->assertEquals('tag', $field->getId());
        $this->assertEquals([
            ['id' => 'cat1.ref1', 'value' => 'Name1 (cat1.ref1)', 'group' => 'category1Name'],
            ['id' => 'cat2.ref2', 'value' => 'Name2 (cat2.ref2)', 'group' => 'category2Name'],
            ['id' => 'cat1.ref3', 'value' => 'Name3 (cat1.ref3)', 'group' => 'category1Name'],
        ], $field->getSelectOptions());
    }

    /** @test */
    public function it_evaluates_to_true_if_group_tagged()
    {
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);

        $group = $this->prophesize(Group::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $group->tags()->shouldBeCalled()->willReturn(collect([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($group->reveal());
        $this->assertTrue($groupTagFilter->evaluate(['tag' => 'reference.ref2']));
    }

    /** @test */
    public function it_evaluates_to_false_if_group_not_tagged()
    {
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);

        $group = $this->prophesize(Group::class);

        $groupTag1 = $this->prophesize(GroupTagModelContract::class);
        $groupTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $groupTag2 = $this->prophesize(GroupTagModelContract::class);
        $groupTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $group->tags()->shouldBeCalled()->willReturn(collect([
            $groupTag1->reveal(),
            $groupTag2->reveal()
        ]));

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($group->reveal());

        $this->assertFalse($groupTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function evaluate_returns_false_if_the_group_tag_repository_throws_an_error()
    {
        $group = $this->prophesize(Group::class);
        $groupTagRepository = $this->prophesize(GroupTagRepositoryContract::class);
        $group->tags()->shouldBeCalled()->willThrow(new \Exception());

        $groupTagFilter = new GroupTagged($groupTagRepository->reveal());
        $groupTagFilter->setModel($group->reveal());
        $this->assertFalse($groupTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function name_returns_a_string()
    {
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $filter = new GroupTagged($this->prophesize(GroupTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->alias());
    }
}

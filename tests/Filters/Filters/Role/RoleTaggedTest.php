<?php


namespace BristolSU\Support\Tests\Filters\Filters\Role;

use BristolSU\ControlDB\Contracts\Models\Tags\RoleTag as RoleTagModelContract;
use BristolSU\ControlDB\Contracts\Repositories\Tags\RoleTag as RoleTagRepositoryContract;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\Tags\RoleTag;
use BristolSU\ControlDB\Models\Tags\RoleTagCategory;
use BristolSU\Support\Filters\Filters\Role\RoleTagged;
use BristolSU\Support\Tests\TestCase;

class RoleTaggedTest extends TestCase
{
    /** @test */
    public function options_returns_a_list_of_possible_tags()
    {
        $roleTagCategory1 = RoleTagCategory::factory()->create(['name' => 'category1Name', 'reference' => 'cat1']);
        $roleTagCategory2 = RoleTagCategory::factory()->create(['name' => 'category2Name', 'reference' => 'cat2']);

        $roleTag1 = RoleTag::factory()->create([
            'tag_category_id' => $roleTagCategory1->id(),
            'name' => 'Name1',
            'reference' => 'ref1'
        ]);

        $roleTag2 = RoleTag::factory()->create([
            'tag_category_id' => $roleTagCategory2->id(),
            'name' => 'Name2',
            'reference' => 'ref2'
        ]);

        $roleTag3 = RoleTag::factory()->create([
            'tag_category_id' => $roleTagCategory1->id(),
            'name' => 'Name3',
            'reference' => 'ref3'
        ]);

        $roleTagRepository = $this->prophesize(RoleTagRepositoryContract::class);
        $roleTagRepository->all()->shouldBeCalled()->willReturn(collect([
            $roleTag1, $roleTag2, $roleTag3
        ]));

        $roleTagFilter = new RoleTagged($roleTagRepository->reveal());

        $this->assertEquals(1, count($roleTagFilter->options()->fields()));
        $this->assertEquals('tag', $roleTagFilter->options()->fields()[0]->model());
        $this->assertEquals('select', $roleTagFilter->options()->fields()[0]->type());
        $this->assertEquals([
            ['id' => 'cat1.ref1', 'name' => 'Name1 (cat1.ref1)', 'role' => 'category1Name'],
            ['id' => 'cat2.ref2', 'name' => 'Name2 (cat2.ref2)', 'role' => 'category2Name'],
            ['id' => 'cat1.ref3', 'name' => 'Name3 (cat1.ref3)', 'role' => 'category1Name'],
        ], $roleTagFilter->options()->fields()[0]->values());
    }

    /** @test */
    public function it_evaluates_to_true_if_role_tagged()
    {
        $roleTagRepository = $this->prophesize(RoleTagRepositoryContract::class);

        $role = $this->prophesize(Role::class);

        $roleTag1 = $this->prophesize(RoleTagModelContract::class);
        $roleTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $roleTag2 = $this->prophesize(RoleTagModelContract::class);
        $roleTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $role->tags()->shouldBeCalled()->willReturn(collect([
            $roleTag1->reveal(),
            $roleTag2->reveal()
        ]));

        $roleTagFilter = new RoleTagged($roleTagRepository->reveal());
        $roleTagFilter->setModel($role->reveal());
        $this->assertTrue($roleTagFilter->evaluate(['tag' => 'reference.ref2']));
    }

    /** @test */
    public function it_evaluates_to_false_if_role_not_tagged()
    {
        $roleTagRepository = $this->prophesize(RoleTagRepositoryContract::class);

        $role = $this->prophesize(Role::class);

        $roleTag1 = $this->prophesize(RoleTagModelContract::class);
        $roleTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $roleTag2 = $this->prophesize(RoleTagModelContract::class);
        $roleTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $role->tags()->shouldBeCalled()->willReturn(collect([
            $roleTag1->reveal(),
            $roleTag2->reveal()
        ]));

        $roleTagFilter = new RoleTagged($roleTagRepository->reveal());
        $roleTagFilter->setModel($role->reveal());

        $this->assertFalse($roleTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function evaluate_returns_false_if_the_role_tag_repository_throws_an_error()
    {
        $role = $this->prophesize(Role::class);
        $roleTagRepository = $this->prophesize(RoleTagRepositoryContract::class);
        $role->tags()->shouldBeCalled()->willThrow(new \Exception());

        $roleTagFilter = new RoleTagged($roleTagRepository->reveal());
        $roleTagFilter->setModel($role->reveal());
        $this->assertFalse($roleTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function name_returns_a_string()
    {
        $filter = new RoleTagged($this->prophesize(RoleTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $filter = new RoleTagged($this->prophesize(RoleTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $filter = new RoleTagged($this->prophesize(RoleTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->alias());
    }
}

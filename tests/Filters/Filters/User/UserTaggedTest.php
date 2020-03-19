<?php


namespace BristolSU\Support\Tests\Filters\Filters\User;


use BristolSU\ControlDB\Contracts\Models\Tags\UserTag as UserTagModelContract;
use BristolSU\ControlDB\Contracts\Repositories\Tags\UserTag as UserTagRepositoryContract;
use BristolSU\ControlDB\Models\User;
use BristolSU\ControlDB\Models\Tags\UserTag;
use BristolSU\ControlDB\Models\Tags\UserTagCategory;
use BristolSU\Support\Filters\Filters\User\UserTagged;
use BristolSU\Support\Tests\TestCase;

class UserTaggedTest extends TestCase
{
    
    /** @test */
    public function options_returns_a_list_of_possible_tags(){

        $userTagCategory1 = factory(UserTagCategory::class)->create(['name' => 'category1Name', 'reference' => 'cat1']);
        $userTagCategory2 = factory(UserTagCategory::class)->create(['name' => 'category2Name', 'reference' => 'cat2']);

        $userTag1 = factory(UserTag::class)->create([
            'tag_category_id' => $userTagCategory1->id(),
            'name' => 'Name1',
            'reference' => 'ref1'
        ]);

        $userTag2 = factory(UserTag::class)->create([
            'tag_category_id' => $userTagCategory2->id(),
            'name' => 'Name2',
            'reference' => 'ref2'
        ]);

        $userTag3 = factory(UserTag::class)->create([
            'tag_category_id' => $userTagCategory1->id(),
            'name' => 'Name3',
            'reference' => 'ref3'
        ]);

        $userTagRepository = $this->prophesize(UserTagRepositoryContract::class);
        $userTagRepository->all()->shouldBeCalled()->willReturn(collect([
            $userTag1, $userTag2, $userTag3
        ]));

        $userTagFilter = new UserTagged($userTagRepository->reveal());
        
        $this->assertEquals(1, count($userTagFilter->options()->fields()));
        $this->assertEquals('tag', $userTagFilter->options()->fields()[0]->model());
        $this->assertEquals('select', $userTagFilter->options()->fields()[0]->type());
        $this->assertEquals([
            ['id' => 'cat1.ref1', 'name' => 'Name1 (cat1.ref1)', 'user' => 'category1Name'],
            ['id' => 'cat2.ref2', 'name' => 'Name2 (cat2.ref2)', 'user' => 'category2Name'],
            ['id' => 'cat1.ref3', 'name' => 'Name3 (cat1.ref3)', 'user' => 'category1Name'],
        ], $userTagFilter->options()->fields()[0]->values());
    }
    
    /** @test */
    public function it_evaluates_to_true_if_user_tagged(){
        $userTagRepository = $this->prophesize(UserTagRepositoryContract::class);

        $user = $this->prophesize(User::class);

        $userTag1 = $this->prophesize(UserTagModelContract::class);
        $userTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $userTag2 = $this->prophesize(UserTagModelContract::class);
        $userTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $user->tags()->shouldBeCalled()->willReturn(collect([
            $userTag1->reveal(),
            $userTag2->reveal()
        ]));

        $userTagFilter = new UserTagged($userTagRepository->reveal());
        $userTagFilter->setModel($user->reveal());
        $this->assertTrue($userTagFilter->evaluate(['tag' => 'reference.ref2']));
    }

    /** @test */
    public function it_evaluates_to_false_if_user_not_tagged(){
        $userTagRepository = $this->prophesize(UserTagRepositoryContract::class);

        $user = $this->prophesize(User::class);

        $userTag1 = $this->prophesize(UserTagModelContract::class);
        $userTag1->fullReference()->shouldBeCalled()->willReturn('reference.ref1');

        $userTag2 = $this->prophesize(UserTagModelContract::class);
        $userTag2->fullReference()->shouldBeCalled()->willReturn('reference.ref2');

        $user->tags()->shouldBeCalled()->willReturn(collect([
            $userTag1->reveal(),
            $userTag2->reveal()
        ]));

        $userTagFilter = new UserTagged($userTagRepository->reveal());
        $userTagFilter->setModel($user->reveal());

        $this->assertFalse($userTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function evaluate_returns_false_if_the_user_tag_repository_throws_an_error(){
        $user = $this->prophesize(User::class);
        $userTagRepository = $this->prophesize(UserTagRepositoryContract::class);
        $user->tags()->shouldBeCalled()->willThrow(new \Exception());

        $userTagFilter = new UserTagged($userTagRepository->reveal());
        $userTagFilter->setModel($user->reveal());
        $this->assertFalse($userTagFilter->evaluate(['tag' => 'reference.ref3']));
    }

    /** @test */
    public function name_returns_a_string(){
        $filter = new UserTagged($this->prophesize(UserTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $filter = new UserTagged($this->prophesize(UserTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string(){
        $filter = new UserTagged($this->prophesize(UserTagRepositoryContract::class)->reveal());
        $this->assertIsString($filter->alias());
    }
}

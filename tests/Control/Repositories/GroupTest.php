<?php


namespace BristolSU\Support\Tests\Control\Repositories;


use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Client\Token;
use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Control\Contracts\Models\GroupTag;
use BristolSU\Support\Control\Repositories\Group;
use BristolSU\Support\Tests\TestCase;

class GroupTest extends TestCase
{

    /** @test */
    public function get_by_id_returns_the_group_with_the_given_id()
    {
        $group = [
            'id' => 1,
            'name' => 'A Group Name',
            'unioncloud_id' => 777,
            'email' => '',
            'created_at' => '2028-01-19 17:26:00',
            'updated_at' => '2028-01-19 17:26:00',
            'deleted_at' => null,
        ];

        $this->mockControl('get', 'groups/' . $group['id'], $group);

        $groupModel = (new Group($this->controlClient->reveal()))->getById($group['id']);
        foreach($group as $attribute => $value) {
            $this->assertEquals($value, $groupModel->{$attribute});
        }
    }

    /** @test */
    public function all_with_tag_returns_all_groups_with_a_given_tag(){
        $groups = [
            ['id' => 1, 'name' => 'A Group Name', 'unioncloud_id' => 777, 'email' => ''],
            ['id' => 2, 'name' => 'A Group Name 2', 'unioncloud_id' => 8889, 'email' => 'tt@bb.com'],
        ];
        $groupTag = $this->prophesize(GroupTag::class);
        $groupTag->id()->shouldBeCalled()->willReturn(1);

        $this->mockControl('get', 'group_tags/1/groups', $groups);

        $groupModels = (new Group($this->controlClient->reveal()))->allWithTag($groupTag->reveal());
        $this->assertEquals(1, $groupModels[0]->id);
        $this->assertEquals(2, $groupModels[1]->id);
    }

    /** @test */
    public function all_returns_all_groups(){
        $groups = [
            ['id' => 1, 'name' => 'A Group Name', 'unioncloud_id' => 777, 'email' => ''],
            ['id' => 2, 'name' => 'A Group Name 2', 'unioncloud_id' => 8889, 'email' => 'tt@bb.com'],
        ];

        $this->mockControl('get', 'groups', $groups);

        $groupModels = (new Group($this->controlClient->reveal()))->all();
        $this->assertEquals(1, $groupModels[0]->id);
        $this->assertEquals(2, $groupModels[1]->id);
    }

}

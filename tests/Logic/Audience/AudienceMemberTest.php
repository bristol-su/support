<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Collection;

class AudienceMemberTest extends TestCase
{
    /** @test */
    public function user_returns_the_user()
    {
        $user = $this->newUser();

        $audienceMember = new AudienceMember($user);
        $this->assertEquals($user, $audienceMember->user());
    }

    /** @test */
    public function groups_returns_the_groups()
    {
        $user = $this->newUser();
        $groups = Group::factory()->count(5)->create();

        $audienceMember = new AudienceMember($user, $groups);
        $this->assertEquals($groups, $audienceMember->groups());
    }

    /** @test */
    public function roles_returns_the_roles()
    {
        $user = $this->newUser();
        $roles = Role::factory()->count(5)->create();

        $audienceMember = new AudienceMember($user, collect(), $roles);
        $this->assertEquals($roles, $audienceMember->roles());
    }

    /** @test */
    public function setGroups_sets_the_groups()
    {
        $user = $this->newUser();
        $groups = Group::factory()->count(5)->create();

        $audienceMember = new AudienceMember($user);
        $this->assertEquals(collect(), $audienceMember->groups());
        $audienceMember->setGroups($groups);
        $this->assertEquals($groups, $audienceMember->groups());
    }

    /** @test */
    public function setRoles_sets_the_roles()
    {
        $user = $this->newUser();
        $roles = Role::factory()->count(5)->create();

        $audienceMember = new AudienceMember($user);
        $this->assertEquals(collect(), $audienceMember->roles());
        $audienceMember->setRoles($roles);
        $this->assertEquals($roles, $audienceMember->roles());
    }

    /** @test */
    public function has_audience_returns_true_if_a_user_can_act_as_themselves()
    {
        $user = $this->newUser();

        $audienceMember = new AudienceMember($user);
        $audienceMember->setCanBeUser(true);
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function has_audience_returns_true_if_a_user_has_groups()
    {
        $user = $this->newUser();
        $groups = Group::factory()->count(3)->create();

        $audienceMember = new AudienceMember($user, $groups);
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function has_audience_returns_true_if_a_user_has_roles()
    {
        $user = $this->newUser();
        $roles = Role::factory()->count(3)->create();

        $audienceMember = new AudienceMember($user, collect(), $roles);

        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function has_audience_returns_false_if_a_user_cannot_be_themselves_or_any_group_or_role()
    {
        $user = $this->newUser();
        $audienceMember = new AudienceMember($user);
        $audienceMember->setCanBeUser(false);

        $this->assertFalse($audienceMember->hasAudience());
    }

    /** @test */
    public function to_array_to_json_and_to_string_returns_attributes()
    {
        $user = $this->newUser();
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $role1 = $this->newRole();
        $role2 = $this->newRole();

        $audienceMember = new AudienceMember($user, collect([$group1, $group2]), collect([$role1, $role2]));

        $this->assertArrayHasKey('user', $audienceMember->toArray());
        $this->assertArrayHasKey('can_be_user', $audienceMember->toArray());
        $this->assertArrayHasKey('groups', $audienceMember->toArray());
        $this->assertArrayHasKey('roles', $audienceMember->toArray());
        $this->assertEquals($user->toArray(), $audienceMember->toArray()['user']);
        $this->assertTrue($audienceMember->toArray()['can_be_user']);
        $this->assertIsArray($audienceMember->toArray()['groups']);
        $this->assertCount(2, $audienceMember->toArray()['groups']);
        $this->assertEquals($group1->toArray(), $audienceMember->toArray()['groups'][0]);
        $this->assertEquals($group2->toArray(), $audienceMember->toArray()['groups'][1]);
        $this->assertIsArray($audienceMember->toArray()['roles']);
        $this->assertCount(2, $audienceMember->toArray()['roles']);
        $this->assertEquals($role1->toArray(), $audienceMember->toArray()['roles'][0]);
        $this->assertEquals($role2->toArray(), $audienceMember->toArray()['roles'][1]);

        $this->assertJson($audienceMember->toJson());
        $this->assertArrayHasKey('user', json_decode($audienceMember->toJson(), true));
        $this->assertArrayHasKey('can_be_user', json_decode($audienceMember->toJson(), true));
        $this->assertArrayHasKey('groups', json_decode($audienceMember->toJson(), true));
        $this->assertArrayHasKey('roles', json_decode($audienceMember->toJson(), true));
        $this->assertEquals($user->id(), json_decode($audienceMember->toJson(), true)['user']['id']);
        $this->assertTrue(json_decode($audienceMember->toJson(), true)['can_be_user']);
        $this->assertCount(2, json_decode($audienceMember->toJson(), true)['groups']);
        $this->assertEquals($group1->id(), json_decode($audienceMember->toJson(), true)['groups'][0]['id']);
        $this->assertEquals($group2->id(), json_decode($audienceMember->toJson(), true)['groups'][1]['id']);
        $this->assertCount(2, json_decode($audienceMember->toJson(), true)['roles']);
        $this->assertEquals($role1->id(), json_decode($audienceMember->toJson(), true)['roles'][0]['id']);
        $this->assertEquals($role2->id(), json_decode($audienceMember->toJson(), true)['roles'][1]['id']);

        $this->assertJson((string) $audienceMember);
        $this->assertArrayHasKey('user', json_decode((string) $audienceMember, true));
        $this->assertArrayHasKey('can_be_user', json_decode((string) $audienceMember, true));
        $this->assertArrayHasKey('groups', json_decode((string) $audienceMember, true));
        $this->assertArrayHasKey('roles', json_decode((string) $audienceMember, true));
        $this->assertEquals($user->id(), json_decode((string) $audienceMember, true)['user']['id']);
        $this->assertTrue(json_decode((string) $audienceMember, true)['can_be_user']);
        $this->assertCount(2, json_decode((string) $audienceMember, true)['groups']);
        $this->assertEquals($group1->id(), json_decode((string) $audienceMember, true)['groups'][0]['id']);
        $this->assertEquals($group2->id(), json_decode((string) $audienceMember, true)['groups'][1]['id']);
        $this->assertCount(2, json_decode((string) $audienceMember, true)['roles']);
        $this->assertEquals($role1->id(), json_decode((string) $audienceMember, true)['roles'][0]['id']);
        $this->assertEquals($role2->id(), json_decode((string) $audienceMember, true)['roles'][1]['id']);
    }

    /** @test */
    public function can_be_user_defaults_to_true()
    {
        $user = $this->newUser();
        $audienceMember = new AudienceMember($user);
        $this->assertTrue($audienceMember->canBeUser());
    }
}

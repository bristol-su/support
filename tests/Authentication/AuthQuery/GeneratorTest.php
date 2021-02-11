<?php

namespace BristolSU\Support\Tests\Authentication\AuthQuery;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\AuthQuery\AuthCredentials;
use BristolSU\Support\Authentication\AuthQuery\Generator;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Tests\TestCase;

class GeneratorTest extends TestCase
{
    /** @test */
    public function get_auth_credentials_fills_the_auth_credential_container()
    {
        $role = $this->newRole();
        $group = $role->group();
        $this->beGroup($group);
        $this->beRole($role);

        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn($activityInstance);

        $generator = new Generator(app(Authentication::class), $activityInstanceResolver->reveal());
        $credentials = $generator->getAuthCredentials();
        $this->assertInstanceOf(AuthCredentials::class, $credentials);
        $this->assertEquals($group->id(), $credentials->groupId());
        $this->assertEquals($role->id(), $credentials->roleId());
        $this->assertEquals($activityInstance->id, $credentials->activityInstanceId());
    }

    /** @test */
    public function get_auth_credentials_passes_the_user_and_group_from_authentication_without_activity_instance()
    {
        $role = $this->newRole();
        $group = $role->group();
        $this->beGroup($group);
        $this->beRole($role);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willThrow(new NotInActivityInstanceException());

        $generator = new Generator(app(Authentication::class), $activityInstanceResolver->reveal());
        $credentials = $generator->getAuthCredentials();
        $this->assertEquals($group->id(), $credentials->groupId());
        $this->assertEquals($role->id(), $credentials->roleId());
        $this->assertNull($credentials->activityInstanceId());
    }

    /** @test */
    public function get_auth_credentials_passes_null_for_user_and_group_from_authentication_if_not_logged_in()
    {
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willThrow(new NotInActivityInstanceException());

        $generator = new Generator(app(Authentication::class), $activityInstanceResolver->reveal());
        $credentials = $generator->getAuthCredentials();
        $this->assertNull($credentials->groupId());
        $this->assertNull($credentials->roleId());
        $this->assertNull($credentials->activityInstanceId());
    }

    /** @test */
    public function get_auth_credentials_passes_just_the_activity_instance_if_given_by_the_resolver()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn($activityInstance);

        $generator = new Generator(app(Authentication::class), $activityInstanceResolver->reveal());
        $credentials = $generator->getAuthCredentials();
        $this->assertInstanceOf(AuthCredentials::class, $credentials);
        $this->assertNull($credentials->groupId());
        $this->assertNull($credentials->roleId());
        $this->assertEquals($activityInstance->id, $credentials->activityInstanceId());
    }
}

<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Logic\AudienceFactory;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use Illuminate\Support\Collection;
use BristolSU\Support\Tests\TestCase;

class  AudienceFactoryTest extends TestCase
{

    /** @test */
    public function for_returns_all_users_if_for_is_user(){
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $userRepo->all()->shouldBeCalled()->willReturn([]);

        $factory = new AudienceFactory($userRepo->reveal(), $groupRepo->reveal(), $roleRepo->reveal());
        $factory->for('user');
    }

    /** @test */
    public function for_returns_all_groups_if_for_is_group(){
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $groupRepo->all()->shouldBeCalled()->willReturn([]);

        $factory = new AudienceFactory($userRepo->reveal(), $groupRepo->reveal(), $roleRepo->reveal());
        $factory->for('group');
    }

    /** @test */
    public function for_returns_all_roles_if_for_is_role(){
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $roleRepo->all()->shouldBeCalled()->willReturn(new Collection);

        $factory = new AudienceFactory($userRepo->reveal(), $groupRepo->reveal(), $roleRepo->reveal());
        $factory->for('role');
    }

    /** @test */
    public function for_returns_an_empty_array_if_for_is_anything_else(){
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $factory = new AudienceFactory($userRepo->reveal(), $groupRepo->reveal(), $roleRepo->reveal());
        $this->assertEquals([],
            $factory->for('somethingelse')
        );
    }

}

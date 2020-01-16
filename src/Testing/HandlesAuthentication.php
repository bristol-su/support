<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Authentication\Contracts\Authentication;

/**
 * Trait for aiding interactions with the user/group/role system
 */
trait HandlesAuthentication
{

    /**
     * Create a new control user
     * 
     * @param array $attributes Attributes to use for the user
     * @return User
     */
    public function newUser($attributes = [])
    {
        return factory(\BristolSU\ControlDB\Models\User::class)->create($attributes);
    }

    /**
     * Create a new control group
     *
     * @param array $attributes Attributes to use for the group
     * @return Group
     */
    public function newGroup($attributes = [])
    {
        return factory(\BristolSU\ControlDB\Models\Group::class)->create($attributes);
    }

    /**
     * Create a new control role
     *
     * @param array $attributes Attributes to use for the role
     * @return Role
     */
    public function newRole($attributes = [])
    {
        return factory(\BristolSU\ControlDB\Models\Role::class)->create($attributes);
    }
    
    /**
     * Set a group
     *
     * @param Group $group
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beGroup(Group $group)
    {
        app()->make(Authentication::class)->setGroup($group);
        // TODO Set API authentication automatically (pass it in the $request function)
    }

    /**
     * Set a role
     *
     * @param Role $role
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beRole(Role $role)
    {
        app()->make(Authentication::class)->setRole($role);
        // TODO Set API authentication automatically (pass it in the $request function)
    }


    /**
     * Set a user
     * 
     * @param User $user
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function beUser(User $user)
    {
        app()->make(Authentication::class)->setUser($user);
        // TODO Set API authentication automatically (pass it in the $request function)
    }



//    /**
//     * @param $method
//     * @param $route
//     * @param null $ability
//     * @param array $parameters
//     */
//    public function assertRequiresAuthorization($method, $route, $ability = null, $parameters = [])
//    {
//        $this->beUser($this->user);
//        $this->beGroup($this->group);
//        $this->beRole($this->role);
//        $this->be($this->databaseUser, 'web');
//        $this->be($this->databaseUser, 'api');
//        $response = $this->call($method, $route, $parameters);
//        $response->assertStatus(403, 'User allowed past authorization without permission. Is there an \'authorize\' statement?');
//
//        $permissionTester = $this->prophesize(PermissionTester::class);
//        $permissionTester->evaluate($this->alias() . '.' . $ability)->shouldBeCalled()->willReturn(true);
//        $this->instance(PermissionTester::class, $permissionTester->reveal());
//
//        $response = $this->call($method, $route, $parameters);
//        $this->assertTrue(
//            $response->isSuccessful(),
//            sprintf('User not allowed past authorization with permission. Status code %s', $response->getStatusCode())
//        );
//
//    }
}
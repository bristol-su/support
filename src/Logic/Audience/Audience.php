<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Create audience members, which are useful for analysing the roles a user can be in , and in relation to logic groups.
 *
 * @method static AudienceMember fromUser(User $user) Create an audience member from a user.
 * @method static Collection withAccessToResource(User|Role|Group $resource) Audience members who have access to a given resource in some way.
 * @method static Collection audience(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = null) Get audience members in a logic group, optionally filtering by the resources used.
 * @method static Collection getUsersInLogicGroup(Logic $logic) Return all users that have access to the logic group
 * @method static Collection getGroupsInLogicGroup(Logic $logic) Return all groups that have access to the logic group
 * @method static Collection getRolesInLogicGroup(Logic $logic) Return all roles that have access to the logic group
 *
 * @see \BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory
 */
class Audience extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory::class;
    }
}

<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;

/**
 * Create an audience member.
 */
interface AudienceMemberFactory
{
    /**
     * Create an audience member from a user.
     *
     * @param User $user User to create the audience member around
     *
     * @return AudienceMember
     */
    public function fromUser(User $user): AudienceMember;

    /**
     * Audience members who have access to a given resource in some way.
     *
     * @param Group|Role|User $resource Resource audience members must have access to
     * @return Collection
     */
    public function withAccessToResource(User|Role|Group $resource): Collection;

    /**
     * Get the audience of a logic group.
     *
     * Will return an array of AudienceMember objects representing the audience of the given logic group.
     *
     * @param Logic $logic Logic group to get the audience for
     * @param User|null $user Filter to only get this users resources
     * @param Group|null $group Filter to only get audience members who are part of the logic group when they have this group
     * @param Role|null $role Filter to only get audience members who are part of the logic group when they have this role
     * @return Collection
     */
    public function audience(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = null): Collection;

    /**
     * Return all users that have access to the logic group.
     * @param Logic $logic
     * @return Collection
     */
    public function getUsersInLogicGroup(Logic $logic): Collection;

    /**
     * Return all groups that have access to the logic group.
     * @param Logic $logic
     * @return Collection
     */
    public function getGroupsInLogicGroup(Logic $logic): Collection;

    /**
     * Return all roles that have access to the logic group.
     * @param Logic $logic
     * @return Collection
     */
    public function getRolesInLogicGroup(Logic $logic): Collection;
}

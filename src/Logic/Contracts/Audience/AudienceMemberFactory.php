<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;

/**
 * Create an audience member
 */
interface AudienceMemberFactory
{

    /**
     * Create an audience member from a user
     * 
     * @param User $user User to create the audience member from
     * 
     * @return AudienceMember
     */
    public function fromUser(User $user);

    /**
     * Audience members who have access to a given resource in some way.
     *
     * This function should return a collection of AudienceMember classes. Each of the audience members
     * must have the $resource included in at least one way.
     * - If the resource is a user, the audience member must be that user
     * - If the resource is a group, the audience member must be a member of that group or have a role in the group
     * - If the resource is a role, the audience member must have that role
     * 
     * @param User|Group|Role $resource Resource audience members must have access to
     * @return Collection
     */
    public function withAccessToResource($resource);

    /**
     * Audience members who have access to a logic group, with a certain resource.
     *
     * This function will return all audience members who have an audience in the logic group which uses a given resource.
     * - If the resource is a user, will contain the user as an Audience Member if in the logic group in some way
     * - If the resource is a group, will contain all audience members in the logic group that are a member of the group, or have a role in group
     * - If the resource is a role, will contain all audience members in the logic group that are a member of the group
     * 
     * @param User|Group|Role $resource Resource that must be in the logic group
     * @param Logic $logic Logic group the resource must be in for an audience member
     *
     * @return Collection
     */
    public function withAccessToLogicGroupWithResource($resource, Logic $logic);

}

<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;

/**
 * Creates an audience member
 */
class AudienceMemberFactory implements AudienceMemberFactoryContract
{

    /**
     * Create an audience member from a user
     * 
     * @param User $user User to create the audience member around
     * 
     * @return AudienceMember
     */
    public function fromUser(User $user): AudienceMember {
        return new AudienceMember($user);
    }

    /**
     * Audience members who have access to a given resource in some way.
     * 
     * @param User|Group|Role $resource Resource audience members must have access to
     * @return Collection
     */
    public function withAccessToResource($resource)
    {
        if ($resource instanceof User) {
            return collect([$this->fromUser($resource)]);
        }
        if ($resource instanceof Group) {
            return $resource->members()->merge(
                $resource->roles()->map(function(Role $role) {
                    return $role->users();
                })->values()->flatten(1)
            )->unique(function($user) {
                return $user->id();
            })->map(function($user) {
                return $this->fromUser($user);
            });
        }
        if ($resource instanceof Role) {
            return $resource->users()->map(function($user) {
                return $this->fromUser($user);
            });
        }
        return collect();
    }

    /**
     * Audience members who have access to a logic group, with a certain resource.
     *
     * This function will return all audience members who have an audience in the logic group which uses a given resource.
     * @param User|Group|Role $resource Resource that must be in the logic group
     * @param Logic $logic Logic group the resource must be in for an audience member
     * 
     * @return Collection
     */
    public function withAccessToLogicGroupWithResource($resource, Logic $logic)
    {
        return $this->withAccessToResource($resource)->map(function(AudienceMember $audienceMember) use ($logic) {
            $audienceMember->filterForLogic($logic);
            return $audienceMember;
        })->filter(function(AudienceMember $audienceMember) use ($resource) {
            // Make sure the audience member has an audience with the given resource
            return ($audienceMember->hasAudience() && $resource instanceof User)
                || ($audienceMember->hasAudience() && $resource instanceof Group && (
                        in_array($resource->id(), $audienceMember->groups()->pluck('id')->toArray())
                        || in_array($resource->id(), $audienceMember->roles()->pluck('group.id')->toArray())
                    ))
                || ($audienceMember->hasAudience() && $resource instanceof Role &&
                    in_array($resource->id(), $audienceMember->roles()->pluck('id')->toArray()));
        })->values();
    }

    /**
     * Create an audience member from a user and filter it down to the given logic
     *
     * @param User $user User to create the audience member from
     *
     * @param Logic $logic
     * @return AudienceMember
     */
    public function fromUserInLogic(User $user, Logic $logic)
    {
        $audienceMember = $this->fromUser($user);
        $audienceMember->filterForLogic($logic);
        return $audienceMember;
    }
}

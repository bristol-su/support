<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Models\Lazy\LazyGroup;
use BristolSU\ControlDB\Models\Lazy\LazyRole;
use BristolSU\ControlDB\Models\Lazy\LazyUser;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;

/**
 * Creates an audience member.
 */
class AudienceMemberFactory implements AudienceMemberFactoryContract
{
    /**
     * Create an audience member from a user.
     *
     * @param User $user User to create the audience member around
     *
     * @return AudienceMember
     */
    public function fromUser(User $user): AudienceMember
    {
        return new AudienceMember($user, $user->groups(), $user->roles());
    }

    /**
     * Audience members who have access to a given resource in some way.
     *
     * @param Group|Role|User $resource Resource audience members must have access to
     * @return Collection
     */
    public function withAccessToResource(User|Role|Group $resource): Collection
    {
        if ($resource instanceof User) {
            return collect([$this->fromUser($resource)]);
        }
        if ($resource instanceof Group) {
            return $resource->members()->merge(
                $resource->roles()->map(function (Role $role) {
                    return $role->users();
                })->values()->flatten(1)
            )->unique(function ($user) {
                return $user->id();
            })->map(function ($user) {
                return $this->fromUser($user);
            });
        }
        return $resource->users()->map(function ($user) {
            return $this->fromUser($user);
        });
    }

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
    public function audience(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = null): Collection
    {
        $conditions = array_filter([
            'user_id' => $user?->id(),
            'group_id' => $group?->id(),
            'role_id' => $role?->id(),
            'result' => true
        ]);

        $audienceMembers = collect();
        $logicResults = LogicResult::forLogic($logic)->where($conditions)->get()->groupBy('user_id');

        foreach($logicResults as $userId => $userLogicResults) {
            $audienceMember = new AudienceMember($user ?? LazyUser::load($userId));
            $audienceMember->setCanBeUser(false);
            $groups = collect();
            $roles = collect();
            foreach($userLogicResults as $userLogicResult) {
                if($userLogicResult->hasRole()) {
                    $roles->push(LazyRole::load($userLogicResult->getRoleId()));
                } elseif ($userLogicResult->hasGroup()) {
                    $groups->push(LazyGroup::load($userLogicResult->getGroupId()));
                } else {
                    $audienceMember->setCanBeUser(true);
                }
            }

            $audienceMember->setGroups($groups);
            $audienceMember->setRoles($roles);

            if($audienceMember->hasAudience()) {
                $audienceMembers->push($audienceMember);
            }
        }

        return $audienceMembers;
    }

    /**
     * Return all users that have access to the logic group
     * @param Logic $logic
     * @return Collection
     */
    public function getUsersInLogicGroup(Logic $logic): Collection
    {
        $userIds = LogicResult::forLogic($logic)
            ->where('result', true)
            ->whereNotNull('user_id')
            ->select('user_id')
            ->distinct()
            ->get();

        return $userIds->map(fn(LogicResult $logicResult) => LazyUser::load($logicResult->user_id));
    }

    /**
     * Return all groups that have access to the logic group
     * @param Logic $logic
     * @return Collection
     */
    public function getGroupsInLogicGroup(Logic $logic): Collection
    {
        $groupIds = LogicResult::forLogic($logic)
            ->where('result', true)
            ->whereNotNull('group_id')
            ->select('group_id')
            ->distinct()
            ->get();

        return $groupIds->map(fn(LogicResult $logicResult) => LazyGroup::load($logicResult->getGroupId()));
    }

    /**
     * Return all roles that have access to the logic group
     * @param Logic $logic
     * @return Collection
     */
    public function getRolesInLogicGroup(Logic $logic): Collection
    {
        $roleIds = LogicResult::forLogic($logic)
            ->where('result', true)
            ->whereNotNull('role_id')
            ->select('role_id')
            ->distinct()
            ->get();

        return $roleIds->map(fn(LogicResult $logicResult) => LazyRole::load($logicResult->getRoleId()));
    }

}

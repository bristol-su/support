<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;

/**
 * Interface LogicAudience
 * @package BristolSU\Support\Logic\Contracts
 */
abstract class LogicAudience
{

    /**
     * @param Logic $logic
     * @return mixed
     */
    abstract public function audience(Logic $logic);

    public function userAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function (AudienceMember $audienceMember) {
            return $audienceMember->canBeUser();
        })->map(function (AudienceMember $audienceMember) {
            return $audienceMember->user();
        })->flatten(1)->unique(function (User $user) {
            return $user->id();
        })->values();
    }

    public function groupAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function (AudienceMember $audienceMember) {
            return $audienceMember->groups()->count() > 0 || $audienceMember->roles()->count() > 0;
        })->map(function (AudienceMember $audienceMember) {
            return $audienceMember->groups()->merge($audienceMember->roles()->map(function(Role $role) {
                return $role->group();
            }));
        })->flatten(1)->unique(function (Group $group) {
            return $group->id();
        })->values();
    }

    public function roleAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function (AudienceMember $audienceMember) {
            return $audienceMember->roles()->count();
        })->map(function (AudienceMember $audienceMember) {
            return $audienceMember->roles();
        })->flatten(1)->unique(function (Role $role) {
            return $role->id();
        })->values();
    }
}

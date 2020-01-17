<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Gets the audience for a logic group
 */
abstract class LogicAudience
{

    /**
     * Gets the audience of a logic group
     * 
     * @param Logic $logic Logic group to get the audience from
     * @return mixed
     */
    abstract public function audience(Logic $logic);

    /**
     * Get the users belonging to a logic group
     * 
     * Extracts the user models from audiences and returns all users who can access the logic group
     * 
     * @param Logic $logic Logic group to test
     * @return \Illuminate\Support\Collection All users who can access the logic group
     */
    public function userAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function(AudienceMember $audienceMember) {
            return $audienceMember->canBeUser(); // TODO Change to hasAudience?
        })->map(function(AudienceMember $audienceMember) {
            return $audienceMember->user();
        })->flatten(1)->unique(function(User $user) {
            return $user->id();
        })->values();
    }

    /**
     * Return all groups in the logic group
     * 
     * @param Logic $logic Logic group to test
     * @return \Illuminate\Support\Collection All groups who can access the logic group
     */
    public function groupAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function(AudienceMember $audienceMember) {
            return $audienceMember->groups()->count() > 0 || $audienceMember->roles()->count() > 0;
        })->map(function(AudienceMember $audienceMember) {
            return $audienceMember->groups()->merge($audienceMember->roles()->map(function(Role $role) {
                return $role->group();
            }));
        })->flatten(1)->unique(function(Group $group) {
            return $group->id();
        })->values();
    }

    /**
     * Returns all roles in the logic group
     *
     * @param Logic $logic Logic group to test
     * @return \Illuminate\Support\Collection All roles who can access the logic group
     */
    public function roleAudience(Logic $logic)
    {
        return collect($this->audience($logic))->filter(function(AudienceMember $audienceMember) {
            return $audienceMember->roles()->count();
        })->map(function(AudienceMember $audienceMember) {
            return $audienceMember->roles();
        })->flatten(1)->unique(function(Role $role) {
            return $role->id();
        })->values();
    }
}

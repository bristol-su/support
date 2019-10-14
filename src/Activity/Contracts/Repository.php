<?php


namespace BristolSU\Support\Activity\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\User\User;

interface Repository
{
    public function active();

    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null);

    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null);

    public function all();

    public function create(array $attributes);

    public function getById($id);
}

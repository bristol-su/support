<?php


namespace BristolSU\Support\Activity\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface Repository
 * @package BristolSU\Support\Activity\Contracts
 */
interface Repository
{
    public function active();

    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return mixed
     */
    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null);

    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return mixed
     */
    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null);

    public function all();

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);
}

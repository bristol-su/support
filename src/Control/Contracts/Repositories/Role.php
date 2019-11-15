<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use BristolSU\Support\Control\Contracts\Models\Group as GroupModel;
use BristolSU\Support\Control\Contracts\Models\Position as PositionModel;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModel;
use BristolSU\Support\Control\Contracts\Models\User as UserModel;
use Illuminate\Support\Collection;

/**
 * Interface Role
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Role
{

    /**
     * Get a role by ID
     *
     * @param $id
     * @return RoleModel
     */
    public function getById($id): RoleModel;

    /**
     * Get all roles belonging to a user
     *
     * @param UserModel $user
     * @return Collection
     */
    public function allThroughUser(UserModel $user): Collection;

    /**
     * Get all roles
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all roles belonging to a group
     * 
     * @param GroupModel $group
     * @return Collection
     */
    public function allThroughGroup(GroupModel $group): Collection;

    /**
     * Get all roles belonging to a position
     * 
     * @param PositionModel $position
     * @return Collection
     */
    public function allThroughPosition(PositionModel $position): Collection;
}

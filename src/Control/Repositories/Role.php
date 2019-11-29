<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModel;
use BristolSU\Support\Control\Contracts\Models\Position as PositionModel;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModel;
use BristolSU\Support\Control\Contracts\Models\User as UserModel;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleContract;
use Illuminate\Support\Collection;

/**
 * Class Role
 * @package BristolSU\Support\Control\Repositories
 */
class Role implements RoleContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * Role constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get a role by ID
     *
     * @param $id
     * @return RoleModel
     */
    public function getById($id): RoleModel
    {
        $role = $this->client->request('get', 'roles/'.$id);
        return new \BristolSU\Support\Control\Models\Role($role);
    }

    /**
     * Get all roles belonging to a user
     *
     * @param UserModel $user
     * @return Collection
     */
    public function allThroughUser(UserModel $user): Collection
    {
        $roles = $this->client->request('get', 'students/'.$user->id().'/roles');
        $modelRoles = new Collection;
        foreach ($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

    /**
     * Get all roles
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $roles = $this->client->request('get', 'roles');
        $modelRoles = new Collection;
        foreach ($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

    /**
     * Get all roles belonging to a group
     *
     * @param GroupModel $group
     * @return Collection
     */
    public function allThroughGroup(GroupModel $group): Collection
    {
        $roles = $this->client->request('get', 'groups/'.$group->id().'/roles');
        $modelRoles = new Collection;
        foreach ($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

    /**
     * Get all roles belonging to a position
     *
     * @param PositionModel $position
     * @return Collection
     */
    public function allThroughPosition(PositionModel $position): Collection
    {
        $roles = $this->client->request('get', 'positions/'.$position->id().'/roles');
        $modelRoles = new Collection;
        foreach ($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }
}
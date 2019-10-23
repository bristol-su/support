<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
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
     * @param $id
     * @return \BristolSU\Support\Control\Models\Role|mixed
     */
    public function getById($id)
    {
        $role = $this->client->request('get', 'roles/' . $id);
        return new \BristolSU\Support\Control\Models\Role($role);
    }

    /**
     * @param $id
     * @return Collection
     */
    public function allFromStudentControlID($id): Collection
    {
        $roles = $this->client->request('get', 'students/' . $id . '/roles');
        $modelRoles = new Collection;
        foreach($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        $roles = $this->client->request('get', 'roles');
        $modelRoles = new Collection;
        foreach($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

}

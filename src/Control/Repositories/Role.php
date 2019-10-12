<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleContract;
use Illuminate\Support\Collection;

class Role implements RoleContract
{

    /**
     * @var ControlClient
     */
    private $client;

    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }
    public function getById($id)
    {
        $role = $this->client->request('get', 'position_student_groups/' . $id);
        return new \BristolSU\Support\Control\Models\Role($role);
    }

    public function allFromStudentControlID($id): Collection
    {
        $roles = $this->client->request('get', 'students/' . $id . 'position_student_groups');
        $modelRoles = new Collection;
        foreach($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

    public function all(): Collection
    {
        $roles = $this->client->request('get', 'position_student_groups');
        $modelRoles = new Collection;
        foreach($roles as $role) {
            $modelRoles->push(new \BristolSU\Support\Control\Models\Role($role));
        }
        return $modelRoles;
    }

}

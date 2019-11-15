<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModel;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModel;
use BristolSU\Support\Control\Contracts\Models\User as UserModelContract;
use BristolSU\Support\Control\Contracts\Repositories\User as UserContract;
use BristolSU\Support\Control\Models\User as UserModel;
use Illuminate\Support\Collection;

/**
 * Class User
 * @package BristolSU\Support\Control\Repositories
 */
class User implements UserContract
{
    /**
     * @var ControlClient
     */
    private $client;

    /**
     * User constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get a user by their ID
     *
     * @param $id
     * @return UserModelContract
     */
    public function getById(int $id): UserModelContract
    {
        $user = $this->client->request('get', 'students/' . $id);
        return new UserModel($user);
    }

    /**
     * Get all users
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $users = $this->client->request('get', 'students');
        $userModels = collect();
        foreach($users as $user) {
            $userModels->push(new UserModel($user));
        }
        return $userModels;
    }

    /**
     * Get a user by their data platform ID
     *
     * @param int $dataPlatformId
     * @return UserModelContract
     */
    public function getByDataPlatformId(int $dataPlatformId): UserModelContract
    {
        $user = $this->client->request('post', 'students/search', [
            'form_params' => ['uc_uid' => $dataPlatformId]
        ]);
        return new UserModel($user[0]);
    }

    /**
     * Create a user
     *
     * @param int $dataPlatformId
     * @return UserModelContract
     */
    public function create(int $dataPlatformId): UserModelContract
    {
        $user = $this->client->request('post', 'students', [
            'form_params' => ['uc_uid' => $dataPlatformId]
        ]);
        return new UserModel($user);
    }

    /**
     * Get all users with a specific role
     *
     * @param RoleModel $role
     * @return Collection
     */
    public function getThroughRole(RoleModel $role): Collection
    {
        $users = $this->client->request('get', 'roles/' . $role->id() . '/students');
        $userModels = collect();
        foreach($users as $user) {
            $userModels->push(new UserModel($user));
        }
        return $userModels;
    }

    /**
     * Get all users of a group
     *
     * @param GroupModel $group
     * @return Collection
     */
    public function getThroughGroup(GroupModel $group): Collection
    {
        $users = $this->client->request('get', 'groups/' . $group->id() . '/students');
        $userModels = collect();
        foreach($users as $user) {
            $userModels->push(new UserModel($user));
        }
        return $userModels;    }
}
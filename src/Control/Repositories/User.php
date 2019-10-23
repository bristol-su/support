<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\User as UserModelContract;
use BristolSU\Support\Control\Contracts\Repositories\User as UserContract;
use BristolSU\Support\Control\Models\User as UserModel;
use GuzzleHttp\Exception\ClientException;

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
     * @param $dataPlatformId
     * @return UserModelContract
     */
    public function findOrCreateByDataId($dataPlatformId): UserModelContract
    {
        try {
            return $this->findByDataId($dataPlatformId);
        } catch (\Exception $e) {
            $user = $this->client->request('post', 'students', [
                'form_params' => ['uc_uid' => $dataPlatformId]
            ]);
            return new UserModel($user);
        }
    }

    /**
     * @param $dataPlatformId
     * @return UserModelContract
     */
    public function findByDataId($dataPlatformId): UserModelContract
    {
        $user = $this->client->request('post', 'students/search', [
            'form_params' => ['uc_uid' => $dataPlatformId]
        ]);
        return new UserModel($user[0]);
    }

    /**
     * @param $id
     * @return UserModelContract
     */
    public function getById($id): UserModelContract
    {
        $user = $this->client->request('get', 'students/' . $id);
        return new UserModel($user);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        $users = $this->client->request('get', 'students');
        $userModels = collect();
        foreach($users as $user) {
            $userModels->push(new UserModel($user));
        }
        return $userModels;
    }
}

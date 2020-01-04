<?php
namespace BristolSU\Support\ActivityInstance\AuthenticationProvider;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserProvider
 * @package BristolSU\Support\Authentication\AuthenticationProvider
 */
class ActivityInstanceProvider implements UserProvider
{

    /**
     * @var ActivityInstanceRepository
     */
    private $repository;

    public function __construct(ActivityInstanceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed $identifier
     * @return \BristolSU\ControlDB\Contracts\Models\User|Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->repository->getById($identifier);

    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|void|null
     */
    public function retrieveByToken($identifier, $token)
    {

    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {

    }

    /**
     * @param array $credentials
     * @return \BristolSU\ControlDB\Contracts\Models\User|Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['activity_instance_id'])) {
            try {
                return $this->retrieveById($credentials['activity_instance_id']);
            } catch (ModelNotFoundException $e) {
            }
        }
        return null;
    }

    /**
     * Ensure the user owns the committee user
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (isset($credentials['activity_instance_id'])) {
            try {
                $this->retrieveById($credentials['activity_instance_id']);
                return true;
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}

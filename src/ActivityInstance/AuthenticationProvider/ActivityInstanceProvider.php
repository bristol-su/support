<?php

namespace BristolSU\Support\ActivityInstance\AuthenticationProvider;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Provider for the current Activity Instance.
 */
class ActivityInstanceProvider implements UserProvider
{
    /**
     * Holds the Activity Instance repository.
     *
     * @var ActivityInstanceRepository
     */
    private $repository;

    /**
     * Initialise the Activity Instance provider.
     *
     * @param ActivityInstanceRepository $repository Repository from which to retrieve the activity instances
     */
    public function __construct(ActivityInstanceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve an Activity Instance by ID.
     *
     * @param mixed $identifier
     * @return ActivityInstance|null
     */
    public function retrieveById($identifier)
    {
        return $this->repository->getById($identifier);
    }

    /**
     * Retrieve an Activity Instance by the remember token.
     *
     * @param mixed $identifier ID of the activity instance
     * @param string $token Remember Token the activity instance needs to have
     *
     * @return Authenticatable|void|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Update the remember token for the Activity Instance.
     *
     * @param Authenticatable $activityInstance Activity Instance
     * @param string $token New remember token to be set
     */
    public function updateRememberToken(Authenticatable $activityInstance, $token)
    {
        return null;
    }

    /**
     * Retrieve an Activity Instance by the credentials.
     *
     * When given correct credentials, will return the Activity Instance.
     * e.g. $credentials = [
     *      'activity_instance_id' => 1
     * ]
     *
     * @param array $credentials
     * @return ActivityInstance|null
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
     * Check the credentials for the Activity Instance.
     *
     * Returns true if credentials are valid. Credentials should look like
     * [
     *      'activity_instance_id' => 1
     * ]
     *
     * @param Authenticatable $user The Activity Instance to validate the credentials against
     * @param array $credentials Credentials to test against the Activity Instance
     * @return bool If the credentials are valid
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

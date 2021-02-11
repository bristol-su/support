<?php

namespace BristolSU\Support\Authentication\AuthQuery;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;

/**
 * Get information about credentials to use to reference the authenticated models.
 */
class Generator
{
    /**
     * @var Authentication
     */
    private Authentication $authentication;

    /**
     * @var ActivityInstanceResolver
     */
    private ActivityInstanceResolver $activityInstanceResolver;

    public function __construct(Authentication $authentication, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->authentication = $authentication;
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Get a representation of the credentials currently logged in.
     *
     * @return AuthCredentials
     */
    public function getAuthCredentials(): AuthCredentials
    {
        $group = $this->authentication->getGroup();
        $role = $this->authentication->getRole();

        try {
            $activityId = $this->activityInstanceResolver->getActivityInstance()->id;
        } catch (NotInActivityInstanceException $e) {
            $activityId = null;
        }

        return new AuthCredentials(
            ($group === null ? null : $group->id()),
            ($role === null ? null : $role->id()),
            $activityId
        );
    }
}

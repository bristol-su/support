<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;

/**
 * Generates a resource ID from the authentication
 */
class AuthenticationResourceIdGenerator implements ResourceIdGenerator
{

    /**
     * Authentication implementation
     *
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise the authentication resource id generator
     *
     * @param Authentication $authentication Authentication implementation
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Generate a resource id
     *
     * Passing a resource type (user, group or role) will get the corresponding model from authentication and return the id
     *
     * @param string $resourceType User, group or role
     * @return int ID of the authenticated model
     *
     * @throws \Exception If not logged into the resourceType model
     */
    public function fromString(string $resourceType): int
    {
        if ($resourceType === 'user') {
            $model = $this->authentication->getUser();
        } elseif ($resourceType === 'group') {
            $model = $this->authentication->getGroup();
        } elseif ($resourceType === 'role') {
            $model = $this->authentication->getRole();
        }
        if ($model === null) {
            throw new \Exception('Not logged into correct model');
        }
        return $model->id();
    }
}

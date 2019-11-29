<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;

class AuthenticationResourceIdGenerator implements ResourceIdGenerator
{

    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function fromString(string $resourceType): int
    {
        if ($resourceType === 'user') {
            $model = $this->authentication->getUser();
        } elseif ($resourceType === 'group') {
            $model = $this->authentication->getGroup();
        } elseif ($resourceType === 'role') {
            $model = $this->authentication->getRole();
        }
        if($model === null) {
            throw new \Exception('Not logged into correct model');
        }
        return $model->id();
    }
}
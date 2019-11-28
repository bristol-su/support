<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class LaravelAuthActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * @var AuthFactory
     */
    private $auth;

    /**
     * UserAuthentication constructor.
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param ActivityInstance $activityInstance
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        $this->auth->guard('activity-instance')->login($activityInstance);
    }

    /**
     * @return ActivityInstance
     * @throws NotInActivityInstanceException
     */
    public function getActivityInstance(): ActivityInstance
    {
        if ($this->auth->check('activity-instance')) {
            return $this->auth->guard('activity-instance')->user();
        }
        throw new NotInActivityInstanceException('No activity instance found', 404);
    }

    public function clearActivityInstance()
    {
        $this->auth->guard('activity-instance')->logout();
    }
}
<?php


namespace BristolSU\Support\ActivityInstance\Contracts;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;

interface ActivityInstanceResolver
{

    public function setActivityInstance(ActivityInstance $activityInstance);

    /**
     * @return ActivityInstance
     * 
     * @throws NotInActivityInstanceException
     */
    public function getActivityInstance(): ActivityInstance;

    public function clearActivityInstance();
    
}
<?php

namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;

/**
 * Get the default activity instance for the given parameters.
 */
interface DefaultActivityInstanceGenerator
{
    /**
     * Get the default activity instance with the given parameters.
     *
     * This function should either find an activity instance, or create one if not found.
     *
     * @param Activity $activity Activity to which the activity instance should belong.
     * @param string $resourceType Resource type. One of user, group or role.
     * @param string $resourceId Resource ID. ID of the user/group/role who owns the activity instance.
     *
     * @return ActivityInstance
     */
    public function generate(Activity $activity, string $resourceType, string $resourceId): ActivityInstance;
}

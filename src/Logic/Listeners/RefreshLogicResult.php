<?php

namespace BristolSU\Support\Logic\Listeners;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Jobs\CacheLogicForRole;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Handles an audience changed event, by clearing the logic cache
 */
class RefreshLogicResult implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function handle(AudienceChanged $audienceChanged)
    {
        foreach($audienceChanged->filterInstances as $filterInstance) {
            $logic = $filterInstance->logic;
            // Only continue if the filter instance is attached to a logic
            if($logic === null) {
                continue;
            }
            $this->refreshFilterResult($logic, $audienceChanged->model);
        }
    }

    public function refreshFilterResult(Logic $logic, User|Group|Role|null $model)
    {
        if($model === null) {
            // Cache the whole logic since we're not limited to a specific model
            dispatch(new CacheLogic($logic));
        }

        if($model instanceof User) {
            dispatch(new CacheLogicForUser([$model], $logic->id));
        }

        if($model instanceof Group) {
            dispatch(new CacheLogicForGroup([$model], $logic->id));
        }

        if($model instanceof Role) {
            dispatch(new CacheLogicForRole([$model], $logic->id));
        }

    }
}

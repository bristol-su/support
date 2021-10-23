<?php

namespace BristolSU\Support\Logic\Listeners;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Logic\Jobs\CacheLogicResult;
use BristolSU\Support\Logic\Jobs\ClearLogicCache;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;

/**
 * Handles an audience changed event, by clearing the logic cache
 */
class RefreshLogicResult implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function handle(AudienceChanged $audienceChanged)
    {
        // Only continue if the filter instance is attached to a logic
        if($audienceChanged->filterInstance->logic === null) {
            return;
        }

        // Get all logic results that match the given model and logic
        $query = LogicResult::forLogic($audienceChanged->filterInstance->logic);
        if ($audienceChanged->model !== null) {
            $query = $query->where(
                match (true) {
                    $audienceChanged->model instanceof User => ['user_id' => $audienceChanged->model->id()],
                    $audienceChanged->model instanceof Group => ['group_id' => $audienceChanged->model->id()],
                    $audienceChanged->model instanceof Role => ['role_id' => $audienceChanged->model->id()],
                }
            );
        }
        $results = $query->get();
logger()->info($results->toJson());
        // Clear and cache the logic results
        foreach ($results as $result) {
            Bus::chain([
                new ClearLogicCache($result),
                new CacheLogicResult(
                    $audienceChanged->filterInstance->logic,
                    $result->user_id,
                    $result->hasGroup() ? $result->group_id : null,
                    $result->hasRole() ? $result->role_id : null
                )
            ])->dispatch();
        }
    }

}

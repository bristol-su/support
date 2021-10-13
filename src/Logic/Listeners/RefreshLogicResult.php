<?php

namespace BristolSU\Support\Logic\Listeners;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Logic\Jobs\CacheLogicResult;
use BristolSU\Support\Logic\Jobs\ClearLogicCache;
use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;


class RefreshLogicResult implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function handle(AudienceChanged $audienceChanged)
    {
        if($audienceChanged->filterInstance->logic === null) {
            throw new \Exception($audienceChanged);
        }
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

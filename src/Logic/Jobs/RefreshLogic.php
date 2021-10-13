<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

/**
 * Job to cache a filter result.
 */
class RefreshLogic implements ShouldQueue
{
    use Queueable;

    private Logic $logic;

    public function __construct(Logic $logic)
    {
        $this->logic = $logic;
    }

    public function handle()
    {
        $results = LogicResult::forLogic($this->logic)->get();

        foreach ($results as $result) {
            Bus::chain([
                new ClearLogicCache($result),
                new CacheLogicResult(
                    $this->logic,
                    $result->user_id,
                    $result->hasGroup() ? $result->group_id : null,
                    $result->hasRole() ? $result->role_id : null
                )
            ])->dispatch();
        }
    }
}

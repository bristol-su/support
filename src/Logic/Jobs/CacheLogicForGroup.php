<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Traits\CachesLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForGroup implements ShouldQueue
{
    use Queueable, CachesLogic, Dispatchable, SerializesModels;

    /**
     * Holds the filter instance to get the result from.
     *
     * @var Group[]|array
     */
    public Collection $groups;

    public ?int $logicId = null;

    /**
     * @param Group[]|array $groups The group to cache logic for
     */
    public function __construct(array $groups, ?int $logicId = null)
    {
        $this->groups = collect($groups);
        $this->logicId = $logicId;
        $this->onQueue('logic');
    }

    /**
     * Handle the job.
     *
     * Test the logic. If the cached decorator is bound to the container, the result will be cached
     */
    public function handle()
    {
        foreach($this->groups as $group) {
            /** @var AudienceMember[] $groupAudience */
            $groupAudience = Audience::withAccessToResource($group);
            foreach($groupAudience as $audience) {
                $audience->roles()->each(
                    fn(Role $role) => $this->cacheLogic($this->logicId, $audience->user(), $role->group(), $role)
                );
                $audience->groups()->each(
                    fn(Group $groupAudience) => $this->cacheLogic($this->logicId, $audience->user(), $groupAudience)
                );
                if($audience->canBeUser()) {
                    $this->cacheLogic($this->logicId, $audience->user());
                }
            }
        }
    }
}

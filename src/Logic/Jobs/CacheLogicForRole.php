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
use Illuminate\Support\Facades\Cache;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForRole implements ShouldQueue
{
    use Queueable, CachesLogic, Dispatchable, SerializesModels;

    /**
     * Holds the filter instance to get the result from.
     *
     * @var array|Role[]
     */
    public Collection $roles;

    public ?int $logicId = null;

    /**
     * @param array|Role[] $roles The role to cache logic for
     */
    public function __construct(array $roles, ?int $logicId = null)
    {
        $this->roles = collect($roles);
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
        foreach ($this->roles as $role) {
            /** @var AudienceMember[] $roleAudience */
            $roleAudience = Audience::withAccessToResource($role);
            foreach ($roleAudience as $audience) {
                $audience->roles()->each(
                    fn (Role $audienceRole) => $this->cacheLogic($this->logicId, $audience->user(), $audienceRole->group(), $audienceRole)
                );
                $audience->groups()->each(
                    fn (Group $group) => $this->cacheLogic($this->logicId, $audience->user(), $group)
                );
                if ($audience->canBeUser()) {
                    $this->cacheLogic($this->logicId, $audience->user());
                }
            }
        }
    }
}

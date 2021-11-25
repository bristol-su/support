<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Traits\CachesLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForUser implements ShouldQueue
{
    use Queueable, CachesLogic;

    /**
     * Holds the filter instance to get the result from.
     *
     * @var array|User[]
     */
    private array $users;

    private ?int $logicId;

    /**
     * @param array|User[] $users The user to cache logic for
     */
    public function __construct(array $users, ?int $logicId = null)
    {
        $this->users = $users;
        $this->logicId = $logicId;
    }

    /**
     * Handle the job.
     *
     * Test the logic. If the cached decorator is bound to the container, the result will be cached
     */
    public function handle()
    {
        foreach($this->users as $user) {
            $audience = Audience::fromUser($user);
            $audience->roles()->each(
                fn(Role $role) => $this->cacheLogic($this->logicId, $user, $role->group(), $role)
            );
            $audience->groups()->each(
                fn(Group $group) => $this->cacheLogic($this->logicId, $user, $group)
            );
            if($audience->canBeUser()) {
                $this->cacheLogic($this->logicId, $user);
            }
        }
    }

}

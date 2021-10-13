<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\Contracts\LogicTester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForUser implements ShouldQueue
{
    use Queueable;

    /**
     * Holds the filter instance to get the result from.
     *
     * @var User
     */
    private $user;
    private ?int $logicId;

    /**
     * @param User $user The user to cache logic for
     */
    public function __construct(User $user, ?int $logicId = null)
    {
        $this->user = $user;
        $this->logicId = $logicId;
    }

    /**
     * Handle the job.
     *
     * Test the logic. If the cached decorator is bound to the container, the result will be cached
     */
    public function handle()
    {
        $audience = Audience::fromUser($this->user);
        $audience->roles()->each(fn(Role $role) => $this->cacheLogic($role->group(), $role));
        $audience->groups()->each(fn(Group $group) => $this->cacheLogic($group));
        if($audience->canBeUser()) {
            $this->cacheLogic();
        }
    }

    private function cacheLogic(?Group $group = null, ?Role $role = null)
    {
        if($this->logicId !== null) {
            app(LogicTester::class)->evaluate(app(LogicRepository::class)->getById($this->logicId), $this->user, $group, $role);
        }
        else {
            foreach(app(LogicRepository::class)->all() as $logic) {
                app(LogicTester::class)->evaluate($logic, $this->user, $group, $role);
            }
        }
    }
}

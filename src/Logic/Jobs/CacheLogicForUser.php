<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Traits\CachesLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForUser implements ShouldQueue
{
    use Queueable, CachesLogic, Dispatchable, SerializesModels;

    /**
     * Holds the filter instance to get the result from.
     *
     * @var array|User[]
     */
    public Collection $users;

    public ?int $logicId = null;

    private array $params = [];

    /**
     * @param array|User[] $users The user to cache logic for
     */
    public function __construct(array $users, ?int $logicId = null)
    {
        $this->params = func_get_args();

        $this->users = collect($users);
        $this->logicId = $logicId;
        $this->onQueue(sprintf('logic_%s', config('app.env')));
    }

    /**
     * Handle the job.
     *
     * Test the logic. If the cached decorator is bound to the container, the result will be cached
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            $audience = Audience::fromUser($user);
            $audience->roles()->each(
                fn (Role $role) => $this->cacheLogic($this->logicId, $user, $role->group(), $role)
            );
            $audience->groups()->each(
                fn (Group $group) => $this->cacheLogic($this->logicId, $user, $group)
            );
            if ($audience->canBeUser()) {
                $this->cacheLogic($this->logicId, $user);
            }
        }
    }

    public function redispatchJob(int $timeout)
    {
//        $this->dispatch(...$this->params)->onConnection($this->connection)->onQueue($this->queue)->delay($timeout);
    }
}

<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Traits\CachesLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Command to cache the result of all filters.
 */
class CacheLogicForSingleCombination implements ShouldQueue
{
    use Queueable, CachesLogic, Dispatchable, SerializesModels;

    public ?int $logicId = null;

    public User $user;

    public ?Group $group = null;

    public ?Role $role = null;
    private array $params;

    /**
     * @param int|null $logicId
     * @param User $user
     * @param Group|null $group
     * @param Role|null $role
     */
    public function __construct(int|null $logicId, User $user, ?Group $group = null, ?Role $role = null)
    {
        $this->params = func_get_args();
        $this->logicId = $logicId;
        $this->user = $user;
        $this->group = $group;
        $this->role = $role;
        $this->onQueue('logic');
    }

    /**
     * Handle the job.
     *
     * Test the logic. If the cached decorator is bound to the container, the result will be cached
     */
    public function handle()
    {
        $this->cacheLogic($this->logicId, $this->user, $this->group, $this->role);
    }

    public function redispatchJob(int $timeout)
    {
        $this->dispatch(...$this->params)->onConnection($this->connection)->onQueue($this->queue)->delay($timeout);
    }
}

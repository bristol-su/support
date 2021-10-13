<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Job to cache a filter result.
 */
class CacheLogicResult implements ShouldQueue
{
    use Queueable;

    private Logic $logic;
    private User|int|null $user;
    private Group|int|null $group;
    private Role|int|null $role;

    public function __construct(Logic $logic, User|int|null $user = null, Group|int|null $group = null, Role|int|null $role = null)
    {
        $this->logic = $logic;
        $this->user = $user;
        $this->group = $group;
        $this->role = $role;
    }

    public function handle(LogicTester $logicTester)
    {
        $logicTester->evaluate(
            $this->logic,
            is_int($this->user) ? app(UserRepository::class)->getById($this->user) : $this->user,
            is_int($this->group) ? app(GroupRepository::class)->getById($this->group) : $this->group,
            is_int($this->role) ? app(RoleRepository::class)->getById($this->role) : $this->role
        );
    }
}

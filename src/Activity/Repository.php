<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Class Repository
 * @package BristolSU\Support\Activity
 */
class Repository implements ActivityRepositoryContract
{
    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return mixed
     */
    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null)
    {
        return $this->active()->filter(function ($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->adminLogic, $user, $group, $role);
        })->values();
    }

    /**
     * @return mixed
     */
    public function  active()
    {
        return Activity::active()->with([
            'moduleInstances',
            'forLogic',
            'adminLogic',
            'moduleInstances.activeLogic',
            'moduleInstances.visibleLogic',
            'moduleInstances.mandatoryLogic',
        ])->get();
    }

    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return mixed
     */
    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null)
    {
        return $this->active()->filter(function ($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->forLogic, $user, $group, $role);
        })->values();
    }

    /**
     * @return Activity[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Activity::all();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return Activity::create($attributes);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Activity::findOrFail($id);
    }
}

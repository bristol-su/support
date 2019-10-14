<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\User\User;

class Repository implements ActivityRepositoryContract
{
    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null)
    {
        return $this->active()->filter(function ($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->adminLogic, $user, $group, $role);
        })->values();
    }

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

    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null)
    {
        return $this->active()->filter(function ($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->forLogic, $user, $group, $role);
        })->values();
    }

    public function all()
    {
        return Activity::all();
    }

    public function create(array $attributes)
    {
        return Activity::create($attributes);
    }


    public function getById($id)
    {
        return Activity::findOrFail($id);
    }
}

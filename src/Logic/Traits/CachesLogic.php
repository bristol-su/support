<?php

namespace BristolSU\Support\Logic\Traits;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;

trait CachesLogic
{
    protected function cacheLogic(int|null $logicId, User $user, ?Group $group = null, ?Role $role = null)
    {
        if($logicId !== null) {
            LogicResult::where([
                'logic_id' => $logicId,
                'user_id' => $user->id(),
                'group_id' => $group?->id(),
                'role_id' => $role?->id()
            ])->first()?->delete();
            $res = app(LogicTester::class)->evaluate(
                app(LogicRepository::class)->getById($logicId),
                $user, $group, $role
            );
            logger()->info(sprintf('Result with position %u is %s', $role?->positionId(), $res ? 'In' : 'Out'));
        }
    else {
            foreach(app(LogicRepository::class)->all() as $logic) {
                LogicResult::where([
                    'logic_id' => $logicId,
                    'user_id' => $user->id(),
                    'group_id' => $group?->id(),
                    'role_id' => $role?->id()
                ])->first()?->delete();
                app(LogicTester::class)->evaluate($logic, $user, $group, $role);
            }
        }
    }
}

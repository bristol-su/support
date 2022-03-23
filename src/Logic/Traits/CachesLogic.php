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
        if ($logicId !== null) {
            $cacheKey = sprintf('is-processing-result-%s-%s-%s-%s', $logicId ?? 'none', $user->id(), $group?->id() ?? 'none', $role?->id() ?? 'none');
            $this->waitUntilReady($cacheKey);
            LogicResult::where([
                'logic_id' => $logicId,
                'user_id' => $user->id(),
                'group_id' => $group?->id(),
                'role_id' => $role?->id()
            ])->first()?->delete();

            app(LogicTester::class)->evaluate(
                app(LogicRepository::class)->getById($logicId),
                $user,
                $group,
                $role
            );
        } else {
            foreach (app(LogicRepository::class)->all() as $logic) {
                $cacheKey = sprintf('is-processing-result-%s-%s-%s-%s', $logic->id, $user->id(), $group?->id() ?? 'none', $role?->id() ?? 'none');
                $this->waitUntilReady($cacheKey);
                LogicResult::where([
                    'logic_id' => $logic->id,
                    'user_id' => $user->id(),
                    'group_id' => $group?->id(),
                    'role_id' => $role?->id()
                ])->first()?->delete();
                app(LogicTester::class)->evaluate($logic, $user, $group, $role);
            }
        }
    }

    private function waitUntilReady(string $key) {
        $i = 0;
        while(cache()->has($key) && $i <= 20) {
            usleep(1000000);
        }
        if($i > 20) {
            throw new \Exception('Timed out waiting for key ' . $key);
        }
        cache()->put($key, true, 35);
    }
}

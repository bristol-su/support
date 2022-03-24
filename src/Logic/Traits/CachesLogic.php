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
            $this->waitUntilReady($cacheKey, function() use ($logicId, $user, $group, $role) {
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
            });

        } else {
            foreach (app(LogicRepository::class)->all() as $logic) {
                $cacheKey = sprintf('is-processing-result-%s-%s-%s-%s', $logic->id, $user->id(), $group?->id() ?? 'none', $role?->id() ?? 'none');
                $this->waitUntilReady($cacheKey, function() use ($logic, $user, $group, $role) {
                    LogicResult::where([
                        'logic_id' => $logic->id,
                        'user_id' => $user->id(),
                        'group_id' => $group?->id(),
                        'role_id' => $role?->id()
                    ])->first()?->delete();
                    app(LogicTester::class)->evaluate($logic, $user, $group, $role);
                });
            }
        }
    }

    private function waitUntilReady(string $key, \Closure $callback) {
        if(cache()->has($key)) {
            $this->redispatchJob(20);
        }
        cache()->put($key, true, 35);
        $callback();
        cache()->forget($key);
    }

    abstract public function redispatchJob(int $timeout);
}

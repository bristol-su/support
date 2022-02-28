<?php

namespace BristolSU\Support\Logic\DatabaseDecorator;

use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Facades\DB;

class LogicDatabaseDecorator implements LogicTester
{
    private LogicTester $baseTester;

    public function __construct(LogicTester $baseTester)
    {
        $this->baseTester = $baseTester;
    }

    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null): bool
    {
        if ($userModel === null) {
            return false;
        }

        $logicResult = LogicResult::forLogic($logic)->withResources($userModel, $groupModel, $roleModel)->first();

        if ($logicResult !== null) {
            return $logicResult->getResult();
        }
        $result = $this->baseTester->evaluate($logic, $userModel, $groupModel, $roleModel);
        DB::transaction(fn () => LogicResult::updateOrCreate([
            'logic_id' => $logic->id,
            'user_id' => $userModel?->id(),
            'group_id' => $groupModel?->id(),
            'role_id' => $roleModel?->id()
        ], [
            'result' => $result
        ]), 5);


        return $result;
    }
}

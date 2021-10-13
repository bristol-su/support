<?php

namespace BristolSU\Support\Logic\DatabaseDecorator;

use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Facades\Log;

class LogicDatabaseDecorator implements LogicTester
{

    private LogicTester $baseTester;

    public function __construct(LogicTester $baseTester)
    {
        $this->baseTester = $baseTester;
    }

    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null): bool
    {
        $logicResult = LogicResult::forLogic($logic)->withResources($userModel, $groupModel, $roleModel)->first();

        if($logicResult !== null) {
            return $logicResult->getResult();
        }
        $result = $this->baseTester->evaluate($logic, $userModel, $groupModel, $roleModel);

        logger()->info(LogicResult::addResult($result, $logic, $userModel, $groupModel, $roleModel));
        return $result;
    }
}

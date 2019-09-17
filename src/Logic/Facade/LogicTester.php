<?php


namespace BristolSU\Support\Logic\Facade;


use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Facades\Facade;

/**
 * Class LogicTester
 * @package BristolSU\Support\Logic\Facade
 *
 * @method static LogicTesterContract evaluate(Logic $logic)
 *
 * @see LogicTesterContract
 */
class LogicTester extends Facade
{

    protected static function getFacadeAccessor()
    {
        return LogicTesterContract::class;
    }

}

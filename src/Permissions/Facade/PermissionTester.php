<?php


namespace BristolSU\Support\Permissions\Facade;


use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Contracts\Tester;
use Illuminate\Support\Facades\Facade;

/**
 * Class PermissionTester
 * @method static bool evaluate(string $ability) Test a Permission
 * @method static void register(Tester $tester) Register a permission tester

 * @package BristolSU\Support\Permissions\Facade
 */
class PermissionTester extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PermissionTesterContract::class;
    }

}

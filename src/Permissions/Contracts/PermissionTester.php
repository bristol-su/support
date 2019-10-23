<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Permissions\Contracts\Testers\Tester;

/**
 * Interface PermissionTester
 * @package BristolSU\Support\Permissions\Contracts
 */
interface PermissionTester
{

    /**
     * @param string $ability
     * @return bool
     */
    public function evaluate(string $ability): bool;

    /**
     * @param Tester $tester
     * @return mixed
     */
    public function register(Tester $tester);

}

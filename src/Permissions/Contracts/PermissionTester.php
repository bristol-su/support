<?php


namespace BristolSU\Support\Permissions\Contracts;


use BristolSU\Support\Permissions\Contracts\Testers\Tester;

interface PermissionTester
{

    public function evaluate(string $ability): bool;

    public function register(Tester $tester);

}

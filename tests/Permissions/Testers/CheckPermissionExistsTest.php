<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\CheckPermissionExists;
use BristolSU\Support\Tests\TestCase;

class CheckPermissionExistsTest extends TestCase
{
    /** @test */
    public function can_returns_false_if_the_permission_is_not_registered(){
        $repository = $this->prophesize(PermissionRepository::class);
        $repository->get('ability')->shouldBeCalled()->willThrow(new \Exception);

        $tester = new CheckPermissionExists($repository->reveal());

        $this->assertFalse(
            $tester->can('ability')
        );
    }

    /** @test */
    public function can_calls_successor_if_the_permission_is_registered(){
        $repository = $this->prophesize(PermissionRepository::class);
        $repository->get('ability')->shouldBeCalled()->willReturn(new Permission);

        $tester = new CheckPermissionExists($repository->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('ability')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('ability');
    }
}

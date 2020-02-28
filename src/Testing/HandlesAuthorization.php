<?php

namespace BristolSU\Support\Testing;

use BristolSU\Support\Permissions\Contracts\PermissionTester;
use Prophecy\Argument;
use Prophecy\Doubler\DoubleInterface;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Trait for aiding interactions with the user/group/role system
 */
trait HandlesAuthorization
{

    /**
     * @var PermissionTester
     */
    protected $permissionTester;

    /**
     * Creates a prophesized permission tester, or returns a previously created one.
     * 
     * @return PermissionTester|ObjectProphecy
     */
    protected function getPermissionTester()
    {
        if(!$this->permissionTester) {
            $this->permissionTester = $this->prophesize(PermissionTester::class); 
        }
        return $this->permissionTester;
    }
    
    /**
     * Bypass any authorization checks made in controllers.
     * 
     * This will simply ensure any call to the permission tester 'evaluate' method returns true.
     * 
     * @return void
     */
    public function bypassAuthorization()
    {
        $this->getPermissionTester()->evaluate(Argument::any())->willReturn(true);
        $this->instance(PermissionTester::class, $this->getPermissionTester()->reveal());
    }

    /**
     * Give the permission to the current user
     * 
     * @param string $ability Ability string to give to the user
     * 
     * @return void
     */
    public function givePermissionTo(string $ability)
    {
        $this->getPermissionTester()->evaluate($ability)->willReturn(true);
        $this->instance(PermissionTester::class, $this->getPermissionTester()->reveal());
    }

    /**
     * Remove the permission from the current user
     *
     * @param string $ability Ability string to remove from the user
     *
     * @return void
     */
    public function revokePermissionTo(string $ability)
    {
        $this->getPermissionTester()->evaluate($ability)->willReturn(false);
        $this->instance(PermissionTester::class, $this->getPermissionTester()->reveal());
    }
    
}
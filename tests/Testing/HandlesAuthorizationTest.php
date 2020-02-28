<?php

namespace BristolSU\Support\Tests\Testing;

use BristolSU\Support\Permissions\Contracts\PermissionTester;
use BristolSU\Support\Permissions\Facade\Permission;
use BristolSU\Support\Testing\HandlesAuthorization;
use BristolSU\Support\Tests\TestCase;

class HandlesAuthorizationTest extends TestCase
{
    use HandlesAuthorization;
    
    /** @test */
    public function bypassAuthorization_ensures_the_permission_tester_always_returns_true(){
        Permission::register('some-ability', 'Name', 'Description', 'alias1', false);
        $this->assertFalse(app(PermissionTester::class)->evaluate('some-ability'));
        $this->bypassAuthorization();
        $this->assertTrue(app(PermissionTester::class)->evaluate('some-ability'));
    }

    /** @test */
    public function givePermissionTo_ensures_the_permission_tester_returns_true_for_the_given_permission(){
        $this->givePermissionTo('some-ability');
        $this->assertTrue(app(PermissionTester::class)->evaluate('some-ability'));
    }

    /** @test */
    public function revokePermissionTo_ensures_the_permission_tester_returns_false_for_the_given_permission(){
        $this->revokePermissionTo('some-ability');
        $this->assertFalse(app(PermissionTester::class)->evaluate('some-ability'));
    }
    
}
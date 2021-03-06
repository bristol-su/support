<?php

namespace BristolSU\Support\Tests\Testing\LogicTester;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Testing\LogicTester\LogicTesterResult;
use BristolSU\Support\Tests\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

class LogicTesterResultTest extends TestCase
{
    /** @test */
    public function if_credentials_are_given_to_pass_evaluate_will_return_true()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $role = factory(Role::class)->create();
            
        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->pass($user, $group, $role);
        
        $this->assertTrue($logicTesterResult->evaluate($user, $group, $role));
    }

    /** @test */
    public function if_credentials_are_given_to_fail_evaluate_will_return_true()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $role = factory(Role::class)->create();

        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->fail($user, $group, $role);

        $this->assertFalse($logicTesterResult->evaluate($user, $group, $role));
    }
    
    /** @test */
    public function if_credentials_are_given_to_pass_and_fail_pass_will_be_preferred()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $role = factory(Role::class)->create();

        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->pass($user, $group, $role)->fail($user, $group, $role);

        $this->assertTrue($logicTesterResult->evaluate($user, $group, $role));
    }

    /** @test */
    public function multiple_credentials_can_be_given_to_pass()
    {
        $user1 = factory(User::class)->create();
        $group1 = factory(Group::class)->create();
        $role1 = factory(Role::class)->create();
        $user2 = factory(User::class)->create();
        $group2 = factory(Group::class)->create();
        $role2 = factory(Role::class)->create();

        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->pass($user1, $group1, $role1)->pass($user2, $group2, $role2);

        $this->assertTrue($logicTesterResult->evaluate($user1, $group1, $role1));
        $this->assertTrue($logicTesterResult->evaluate($user2, $group2, $role2));
    }

    /** @test */
    public function multiple_credentials_can_be_given_to_fail()
    {
        $user1 = factory(User::class)->create();
        $group1 = factory(Group::class)->create();
        $role1 = factory(Role::class)->create();
        $user2 = factory(User::class)->create();
        $group2 = factory(Group::class)->create();
        $role2 = factory(Role::class)->create();

        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->fail($user1, $group1, $role1)->fail($user2, $group2, $role2);

        $this->assertFalse($logicTesterResult->evaluate($user1, $group1, $role1));
        $this->assertFalse($logicTesterResult->evaluate($user2, $group2, $role2));
    }
    
    /** @test */
    public function if_credentials_do_not_match_the_default_is_given()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $role = factory(Role::class)->create();
        
        $logicTesterResult = new LogicTesterResult();
        $logicTesterResult->otherwise(true);
        
        $this->assertTrue($logicTesterResult->evaluate($user, $group, $role));
    }

    /** @test */
    public function if_credentials_do_not_match_and_no_default_is_given_false_is_returned()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $role = factory(Role::class)->create();

        $logicTesterResult = new LogicTesterResult();

        $this->assertFalse($logicTesterResult->evaluate($user, $group, $role));
    }
    
    /** @test */
    public function it_asserts_incorrect_if_required_logic_test_not_called()
    {
        $this->expectException(ExpectationFailedException::class);
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        
        (new LogicTesterResult())
            ->pass($user1)
            ->fail($user2)
            ->shouldBeCalled($user2);
    }

    /** @test */
    public function it_asserts_correct_if_required_logic_test_called()
    {
        $user = factory(User::class)->create();

        $logicTesterResult = (new LogicTesterResult())
            ->fail($user)
            ->shouldBeCalled($user);
        
        $logicTesterResult->evaluate($user);
    }
    
    /** @test */
    public function always_true_always_returns_true_from_evaluate()
    {
        $logicTesterResult = (new LogicTesterResult());
        $logicTesterResult->pass()->alwaysFail();
        
        $this->assertFalse($logicTesterResult->evaluate());
    }

    /** @test */
    public function always_false_always_returns_false_from_evaluate()
    {
        $logicTesterResult = (new LogicTesterResult());
        $logicTesterResult->fail()->alwaysPass();

        $this->assertTrue($logicTesterResult->evaluate());
    }
}

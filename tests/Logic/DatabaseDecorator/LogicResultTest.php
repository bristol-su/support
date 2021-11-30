<?php

namespace BristolSU\Support\Tests\Logic\DatabaseDecorator;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class LogicResultTest extends TestCase
{

    /** @test */
    public function forLogic_returns_only_results_from_a_certain_logic(){
        $logic = Logic::factory()->create();
        $results = LogicResult::factory()->count(10)->create();
        $result = LogicResult::factory()->count(4)->create(['logic_id' => $logic->id]);

        $this->assertEquals(4, LogicResult::forLogic($logic)->count());
    }

    /** @test */
    public function withResources_scopes_to_the_given_resources(){
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $role = Role::factory()->create();
        $group = Group::factory()->create();

        $result = LogicResult::factory()->forUser($user)->forRole($role)->create();
        LogicResult::factory()->forUser($user2)->forRole($role)->create();
        LogicResult::factory()->forUser($user)->forGroup($group)->create();

        $this->assertModelEquals($result, LogicResult::withResources($user, $role->group(), $role)->first());
    }

    /** @test */
    public function getResult_gets_the_result(){
        $trueLogic = LogicResult::factory()->passing()->create();
        $falseLogic = LogicResult::factory()->rejecting()->create();

        $this->assertTrue($trueLogic->getResult());
        $this->assertFalse($falseLogic->getResult());
    }

    /** @test */
    public function hasGroup_returns_if_a_group_exists(){
        $groupLogic = LogicResult::factory()->forGroup()->create();
        $userLogic = LogicResult::factory()->create();

        $this->assertTrue($groupLogic->hasGroup());
        $this->assertFalse($userLogic->hasGroup());
    }

    /** @test */
    public function hasRole_returns_if_a_role_exists(){
        $roleLogic = LogicResult::factory()->forRole()->create();
        $userLogic = LogicResult::factory()->create();

        $this->assertTrue($roleLogic->hasRole());
        $this->assertFalse($userLogic->hasRole());
    }

    /** @test */
    public function getGroupId_returns_the_group_id(){
        $groupLogic = LogicResult::factory()->forGroup()->create();
        $userLogic = LogicResult::factory()->create();

        $this->assertEquals($groupLogic->group_id, $groupLogic->getGroupId());
        $this->assertFalse($userLogic->hasGroup());
    }

    /** @test */
    public function getRoleId_returns_the_role_id(){
        $roleLogic = LogicResult::factory()->forRole()->create();
        $userLogic = LogicResult::factory()->create();

        $this->assertEquals($roleLogic->role_id, $roleLogic->getRoleId());
        $this->assertFalse($userLogic->hasRole());
    }

    /** @test */
    public function addResult_adds_a_new_logic_result_model()
    {
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $logicResult = LogicResult::addResult(true, $logic, $user, $group, null);

        $this->assertDatabaseHas('logic_results', ['logic_id' => $logic->id, 'user_id' => $user->id(), 'group_id' => $group->id(), 'result' => true]);
    }

}

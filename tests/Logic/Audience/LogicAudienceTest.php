<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\LogicAudience;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class LogicAudienceTest extends TestCase
{

   
    /** @test */
    public function it_creates_an_audience_member_for_each_user(){
        $logic = factory(Logic::class)->create();
        
        $user1 = $this->newUser(['id' => 1]);
        $user2 = $this->newUser(['id' => 2]);
        $user3 = $this->newUser(['id' => 3]);
        $audienceMember1 = $this->prophesize(AudienceMember::class);
        $audienceMember2 = $this->prophesize(AudienceMember::class);
        $audienceMember3 = $this->prophesize(AudienceMember::class);

        $users = collect([$user1, $user2, $user3]);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->all()->shouldBeCalled()->willReturn($users);
        
        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUser($user1)->shouldBeCalled()->willReturn($audienceMember1->reveal());
        $audienceMemberFactory->fromUser($user2)->shouldBeCalled()->willReturn($audienceMember2->reveal());
        $audienceMemberFactory->fromUser($user3)->shouldBeCalled()->willReturn($audienceMember3->reveal());
        
        $logicAudience = new LogicAudience($userRepository->reveal(), $audienceMemberFactory->reveal());
        $logicAudience->audience($logic);
    }
    
    /** @test */
    public function it_calls_filterForLogic_on_each_audience_member(){
        $logic = factory(Logic::class)->create();

        $user1 = $this->newUser(['id' => 1]);
        $user2 = $this->newUser(['id' => 2]);
        $user3 = $this->newUser(['id' => 3]);
        $audienceMember1 = $this->prophesize(AudienceMember::class);
        $audienceMember2 = $this->prophesize(AudienceMember::class);
        $audienceMember3 = $this->prophesize(AudienceMember::class);
        $audienceMember1->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember2->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember3->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();

        $audienceMember1->hasAudience()->shouldBeCalled()->willReturn(false);
        $audienceMember2->hasAudience()->shouldBeCalled()->willReturn(false);
        $audienceMember3->hasAudience()->shouldBeCalled()->willReturn(false);

        $users = collect([$user1, $user2, $user3]);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->all()->shouldBeCalled()->willReturn($users);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUser($user1)->shouldBeCalled()->willReturn($audienceMember1->reveal());
        $audienceMemberFactory->fromUser($user2)->shouldBeCalled()->willReturn($audienceMember2->reveal());
        $audienceMemberFactory->fromUser($user3)->shouldBeCalled()->willReturn($audienceMember3->reveal());

        $logicAudience = new LogicAudience($userRepository->reveal(), $audienceMemberFactory->reveal());
        $logicAudience->audience($logic);
    }
    
    /** @test */
    public function it_calls_hasAudience_on_each_audience_member_and_returns_them_if_true(){
        $logic = factory(Logic::class)->create();

        $user1 = $this->newUser(['id' => 1]);
        $user2 = $this->newUser(['id' => 2]);
        $user3 = $this->newUser(['id' => 3]);
        $audienceMember1 = $this->prophesize(AudienceMember::class);
        $audienceMember2 = $this->prophesize(AudienceMember::class);
        $audienceMember3 = $this->prophesize(AudienceMember::class);
        $audienceMember1->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember2->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember3->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();

        $audienceMember1->hasAudience()->shouldBeCalled()->willReturn(false);
        $audienceMember2->hasAudience()->shouldBeCalled()->willReturn(true);
        $audienceMember3->hasAudience()->shouldBeCalled()->willReturn(true);

        $users = collect([$user1, $user2, $user3]);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->all()->shouldBeCalled()->willReturn($users);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUser($user1)->shouldBeCalled()->willReturn($audienceMember1->reveal());
        $audienceMemberFactory->fromUser($user2)->shouldBeCalled()->willReturn($audienceMember2->reveal());
        $audienceMemberFactory->fromUser($user3)->shouldBeCalled()->willReturn($audienceMember3->reveal());

        $logicAudience = new LogicAudience($userRepository->reveal(), $audienceMemberFactory->reveal());
        $this->assertEquals([$audienceMember2->reveal(), $audienceMember3->reveal()], $logicAudience->audience($logic));
    }
    
    /** @test */
    public function it_returns_an_empty_array_if_no_audience_found(){
        $logic = factory(Logic::class)->create();

        $user1 = $this->newUser(['id' => 1]);
        $user2 = $this->newUser(['id' => 2]);
        $user3 = $this->newUser(['id' => 3]);
        $audienceMember1 = $this->prophesize(AudienceMember::class);
        $audienceMember2 = $this->prophesize(AudienceMember::class);
        $audienceMember3 = $this->prophesize(AudienceMember::class);
        $audienceMember1->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember2->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();
        $audienceMember3->filterForLogic(Argument::that(function($arg) use ($logic) {
            return $arg->id === $logic->id;
        }))->shouldBeCalledOnce();

        $audienceMember1->hasAudience()->shouldBeCalled()->willReturn(false);
        $audienceMember2->hasAudience()->shouldBeCalled()->willReturn(false);
        $audienceMember3->hasAudience()->shouldBeCalled()->willReturn(false);

        $users = collect([$user1, $user2, $user3]);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->all()->shouldBeCalled()->willReturn($users);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUser($user1)->shouldBeCalled()->willReturn($audienceMember1->reveal());
        $audienceMemberFactory->fromUser($user2)->shouldBeCalled()->willReturn($audienceMember2->reveal());
        $audienceMemberFactory->fromUser($user3)->shouldBeCalled()->willReturn($audienceMember3->reveal());

        $logicAudience = new LogicAudience($userRepository->reveal(), $audienceMemberFactory->reveal());
        $this->assertEquals([], $logicAudience->audience($logic));
    }
    
    /** @test */
    public function userAudience_gets_all_unique_users_from_the_audience(){
        $logic = factory(Logic::class)->create();
        $user1 = $this->newUser();
        $user2 = $this->newUser();
        
        // Has a unique user #1
        $audience1 = $this->prophesize(AudienceMember::class);
        $audience1->hasAudience()->shouldBeCalled()->willReturn(true);
        $audience1->user()->shouldBeCalled()->willReturn($user1);

        // Duplicate user #2 
        $audience2 = $this->prophesize(AudienceMember::class);
        $audience2->hasAudience()->shouldBeCalled()->willReturn(true);
        $audience2->user()->shouldBeCalled()->willReturn($user2);
        $audience3 = $this->prophesize(AudienceMember::class);
        $audience3->hasAudience()->shouldBeCalled()->willReturn(true);
        $audience3->user()->shouldBeCalled()->willReturn($user2);

        // Not in audience #3
        $audience4 = $this->prophesize(AudienceMember::class);
        $audience4->hasAudience()->shouldBeCalled()->willReturn(false);
        $audience4->user()->shouldNotBeCalled();
        
        $logicAudience = new DummyLogicAudience();
        $logicAudience->addAudience($audience1->reveal());
        $logicAudience->addAudience($audience2->reveal());
        $logicAudience->addAudience($audience3->reveal());
        $logicAudience->addAudience($audience4->reveal());

        $extractedUsers = $logicAudience->userAudience($logic);
        
        $this->assertEquals(2, $extractedUsers->count());
        $this->assertContainsOnlyInstancesOf(\BristolSU\ControlDB\Contracts\Models\User::class, $extractedUsers);
        $this->assertModelEquals($user1, $extractedUsers->offsetGet(0));
        $this->assertModelEquals($user2, $extractedUsers->offsetGet(1));
    
    }

    /** @test */
    public function groupAudience_gets_all_groups_from_the_audience(){
        $logic = factory(Logic::class)->create();
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $group3 = $this->newGroup();

        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role3 = $this->newRole();

        // Has unique groups #1 #2
        $audience1 = $this->prophesize(AudienceMember::class);
        $audience1->groups()->shouldBeCalled()->willReturn(collect([$group1, $group2]));
        $audience1->roles()->shouldBeCalled()->willReturn(collect());

        // Has unique roles #1 and #2
        $audience2 = $this->prophesize(AudienceMember::class);
        $audience2->groups()->shouldBeCalled()->willReturn(collect());
        $audience2->roles()->shouldBeCalled()->willReturn(collect([$role1, $role2]));

        // Has duplicate groups #3
        $audience3 = $this->prophesize(AudienceMember::class);
        $audience3->groups()->shouldBeCalled()->willReturn(collect([$group3]));
        $audience3->roles()->shouldBeCalled()->willReturn(collect());
        $audience4 = $this->prophesize(AudienceMember::class);
        $audience4->groups()->shouldBeCalled()->willReturn(collect([$group3]));
        $audience4->roles()->shouldBeCalled()->willReturn(collect());

        // Has duplicate roles #3
        $audience5 = $this->prophesize(AudienceMember::class);
        $audience5->groups()->shouldBeCalled()->willReturn(collect());
        $audience5->roles()->shouldBeCalled()->willReturn(collect([$role3]));
        $audience6 = $this->prophesize(AudienceMember::class);
        $audience6->groups()->shouldBeCalled()->willReturn(collect());
        $audience6->roles()->shouldBeCalled()->willReturn(collect([$role3]));

        // No roles or groups
        $audience7 = $this->prophesize(AudienceMember::class);
        $audience7->groups()->shouldBeCalled()->willReturn(collect());
        $audience7->roles()->shouldBeCalled()->willReturn(collect());
        
        $logicAudience = new DummyLogicAudience();
        $logicAudience->addAudience($audience1->reveal());
        $logicAudience->addAudience($audience2->reveal());
        $logicAudience->addAudience($audience3->reveal());
        $logicAudience->addAudience($audience4->reveal());
        $logicAudience->addAudience($audience5->reveal());
        $logicAudience->addAudience($audience6->reveal());
        $logicAudience->addAudience($audience7->reveal());
        
        $extractedGroups = $logicAudience->groupAudience($logic);
        
        $this->assertEquals(6, $extractedGroups->count());
        $this->assertContainsOnlyInstancesOf(\BristolSU\ControlDB\Contracts\Models\Group::class, $extractedGroups);
        $this->assertModelEquals($group1, $extractedGroups->offsetGet(0));
        $this->assertModelEquals($group2, $extractedGroups->offsetGet(1));
        $this->assertModelEquals($role1->group(), $extractedGroups->offsetGet(2));
        $this->assertModelEquals($role2->group(), $extractedGroups->offsetGet(3));
        $this->assertModelEquals($group3, $extractedGroups->offsetGet(4));
        $this->assertModelEquals($role3->group(), $extractedGroups->offsetGet(5));
    }

    /** @test */
    public function roleAudience_gets_all_roles_from_the_audience(){
        $logic = factory(Logic::class)->create();
        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role3 = $this->newRole();

        // Has a unique roles #1 #2
        $audience1 = $this->prophesize(AudienceMember::class);
        $audience1->roles()->shouldBeCalled()->willReturn(collect([$role1, $role2]));

        // Duplicate role #3 
        $audience2 = $this->prophesize(AudienceMember::class);
        $audience2->roles()->shouldBeCalled()->willReturn(collect([$role3]));
        $audience3 = $this->prophesize(AudienceMember::class);
        $audience3->roles()->shouldBeCalled()->willReturn(collect([$role3]));

        // Not in audience
        $audience4 = $this->prophesize(AudienceMember::class);
        $audience4->roles()->shouldBeCalled()->willReturn(collect());

        $logicAudience = new DummyLogicAudience();
        $logicAudience->addAudience($audience1->reveal());
        $logicAudience->addAudience($audience2->reveal());
        $logicAudience->addAudience($audience3->reveal());
        $logicAudience->addAudience($audience4->reveal());

        $extractedRoles = $logicAudience->roleAudience($logic);

        $this->assertEquals(3, $extractedRoles->count());
        $this->assertContainsOnlyInstancesOf(\BristolSU\ControlDB\Contracts\Models\Role::class, $extractedRoles);
        $this->assertModelEquals($role1, $extractedRoles->offsetGet(0));
        $this->assertModelEquals($role2, $extractedRoles->offsetGet(1));
        $this->assertModelEquals($role3, $extractedRoles->offsetGet(2));
    }
    
}


class DummyLogicAudience extends \BristolSU\Support\Logic\Contracts\Audience\LogicAudience {

    private $audience;
    
    /**
     * @inheritDoc
     */
    public function audience(Logic $logic)
    {
        return $this->audience;
    }

    public function addAudience($audience) {
        $this->audience[] = $audience;
    }
}
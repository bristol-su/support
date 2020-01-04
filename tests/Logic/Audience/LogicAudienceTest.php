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

    /**
     * For each user in the repository, it should create an audience member
     * Each audience member will have filterForLogic called, passing in the logic
     * Each one will have hasAudience called. If true, they will be returned in the array
     */
    
    /** @test */
    public function it_creates_an_audience_member_for_each_user(){
        $logic = factory(Logic::class)->create();
        
        $user1 = new User(['id' => 1]);
        $user2 = new User(['id' => 2]);
        $user3 = new User(['id' => 3]);
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

        $user1 = new User(['id' => 1]);
        $user2 = new User(['id' => 2]);
        $user3 = new User(['id' => 3]);
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

        $user1 = new User(['id' => 1]);
        $user2 = new User(['id' => 2]);
        $user3 = new User(['id' => 3]);
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

        $user1 = new User(['id' => 1]);
        $user2 = new User(['id' => 2]);
        $user3 = new User(['id' => 3]);
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
    public function userAudience_gets_all_users_from_the_audience(){
        $this->markTestIncomplete();
    }

    /** @test */
    public function groupAudience_gets_all_groups_from_the_audience(){
        $this->markTestIncomplete();
    }

    /** @test */
    public function roleAudience_gets_all_roles_from_the_audience(){
        $this->markTestIncomplete();
    }
    
}

<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\CachedAudienceMemberFactory;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Cache\Repository;
use Prophecy\Argument;

class CachedAudienceMemberFactoryTest extends TestCase
{
    /** @test */
    public function with_access_to_resource_is_cached()
    {
        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $group = $this->newGroup();
        $am1 = new AudienceMember($user1);
        $am2 = new AudienceMember($user2);
        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToResource(Argument::that(function ($arg) use ($group) {
            return $arg instanceof Group && $group->id() === $arg->id();
        }))->shouldBeCalled()->willReturn(collect([$am1, $am2]));
        
        $cache = app(Repository::class);
        
        $cachedAudienceMemberFactory = new CachedAudienceMemberFactory($audienceMemberFactory->reveal(), $cache);
        $result = $cachedAudienceMemberFactory->withAccessToResource($group);
        $this->assertCount(2, $result);
        $this->assertEquals($am1, $result[0]);
        $this->assertEquals($am2, $result[1]);
        $this->assertTrue($cache->has(
            'BristolSU\Support\Logic\Audience\CachedAudienceMemberFactory@withAccessToResource:BristolSU\ControlDB\Models\Group:' . $group->id()
        ));
    }

    /** @test */
    public function with_access_to_logic_group_with_resource_is_cached()
    {
        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $group = $this->newGroup();
        $logic = factory(Logic::class)->create();
        $am1 = new AudienceMember($user1);
        $am2 = new AudienceMember($user2);
        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function ($arg) use ($group) {
            return $arg instanceof Group && $group->id() === $arg->id();
        }), Argument::that(function ($arg) use ($logic) {
            return $arg instanceof Logic && $arg->id === $logic->id;
        }))->shouldBeCalled()->willReturn(collect([$am1, $am2]));

        $cache = app(Repository::class);

        $cachedAudienceMemberFactory = new CachedAudienceMemberFactory($audienceMemberFactory->reveal(), $cache);
        $result = $cachedAudienceMemberFactory->withAccessToLogicGroupWithResource($group, $logic);
        $this->assertCount(2, $result);
        $this->assertEquals($am1, $result[0]);
        $this->assertEquals($am2, $result[1]);
        $this->assertTrue($cache->has(
            'BristolSU\Support\Logic\Audience\CachedAudienceMemberFactory@withAccessToLogicGroupWithResource:BristolSU\ControlDB\Models\Group:'
            . $group->id() . ':' . $logic->id
        ));
    }
    
    /** @test */
    public function from_user_in_logic_is_cached()
    {
        $user = $this->newUser();
        $logic = factory(Logic::class)->create();
        $am = new AudienceMember($user);
        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUserInLogic(Argument::that(function ($arg) use ($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function ($arg) use ($logic) {
            return $arg instanceof Logic && $arg->id === $logic->id;
        }))->shouldBeCalled()->willReturn($am);

        $cache = app(Repository::class);

        $cachedAudienceMemberFactory = new CachedAudienceMemberFactory($audienceMemberFactory->reveal(), $cache);
        $result = $cachedAudienceMemberFactory->fromUserInLogic($user, $logic);
        $this->assertEquals($am, $result);
        $this->assertTrue($cache->has(
            'BristolSU\Support\Logic\Audience\CachedAudienceMemberFactory@fromUserInLogic:' . $user->id() . ':' . $logic->id
        ));
    }
    
    /** @test */
    public function from_user_is_not_cached()
    {
        $user = $this->newUser();
        $am = new AudienceMember($user);
        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->fromUser(Argument::that(function ($arg) use ($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }))->shouldBeCalled()->willReturn($am);

        $cache = $this->prophesize(Repository::class);
        $cache->remember(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        
        $cachedAudienceMemberFactory = new CachedAudienceMemberFactory($audienceMemberFactory->reveal(), $cache->reveal());
        $result = $cachedAudienceMemberFactory->fromUser($user);
        $this->assertEquals($am, $result);
    }
}

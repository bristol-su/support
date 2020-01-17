<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class  AudienceMemberFactoryTest extends TestCase
{
    
    /** @test */
    public function fromUser_creates_an_audience_member_from_a_given_user(){
        $user = $this->newUser(['id' => 1]);
        $factory = new AudienceMemberFactory;
        $audienceMember = $factory->fromUser($user);

        $this->assertInstanceOf(AudienceMember::class, $audienceMember);
        $this->assertEquals($user, $audienceMember->user());
    }
    
    /** @test */
    public function withAccessToResource_returns_a_single_audience_member_if_resource_is_a_user(){
        $resource = $this->newUser();
        
        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);
        
        $this->assertCount(1, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($resource, $audienceMembers[0]->user());
    }

    /** @test */
    public function withAccessToResource_returns_audiences_with_the_group_in_if_resource_is_a_group(){
        $resource = $this->newGroup();
        $role1 = $this->newRole(['group_id' => $resource->id]);
        $role2 = $this->newRole(['group_id' => $resource->id]);

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user3 = $this->newUser();
        $user4 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);
        $role1->addUser($user3);
        $role1->addUser($user4);
        $role2->addUser($user4);

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);

        $this->assertCount(4, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        $this->assertModelEquals($user2, $audienceMembers[1]->user());
        $this->assertModelEquals($user3, $audienceMembers[2]->user());
        $this->assertModelEquals($user4, $audienceMembers[3]->user());
        
    }

    /** @test */
    public function withAccessToResource_returns_audiences_with_the_role_in_if_resource_is_a_role(){
        $resource = $this->newRole();

        $user1 = $this->newUser();
        $user2 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);

        $this->assertCount(2, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        $this->assertModelEquals($user2, $audienceMembers[1]->user());
    }

    /** @test */
    public function withAccessToLogicGroupWithResource_returns_a_single_audience_member_if_resource_is_a_user(){
        $resource = $this->newUser();
        $logic = factory(Logic::class)->create();

        $this->logicTester()->forLogic($logic)->shouldBeCalled($resource)->pass($resource);
        $this->logicTester()->bind();
        
        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToLogicGroupWithResource($resource, $logic);

        $this->assertCount(1, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($resource, $audienceMembers[0]->user());
    }

    /** @test */
    public function withAccessToLogicGroupWithResource_returns_audiences_with_the_group_in_if_resource_is_a_group(){
        $resource = $this->newGroup();
        $role1 = $this->newRole(['group_id' => $resource->id]);
        $role2 = $this->newRole(['group_id' => $resource->id]);
        $logic = factory(Logic::class)->create();

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user3 = $this->newUser();
        $user4 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);
        $role1->addUser($user3);
        $role2->addUser($user4);

        $this->logicTester()->forLogic($logic)
            ->pass($user1, $resource, null)
            ->pass($user3, $role1->group(), $role1)
            ->otherwise(false);
        $this->logicTester()->bind();
        
        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToLogicGroupWithResource($resource, $logic);
        
        $this->assertCount(2, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        $this->assertModelEquals($user3, $audienceMembers[1]->user());
        
    }

    /** @test */
    public function withAccessToLogicGroupWithResource_returns_audiences_with_the_role_in_if_resource_is_a_role(){
        $resource = $this->newRole();
        $role2 = $this->newRole();
        $logic = factory(Logic::class)->create();

        $user1 = $this->newUser();
        $user2 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);
        $role2->addUser($user1);
        $role2->addUser($user2);

        $this->logicTester()->forLogic($logic)
            ->pass($user1, $resource->group(), $resource)
            ->fail($user2, $resource->group(), $resource)
            ->otherwise(false);
        $this->logicTester()->bind();
        
        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToLogicGroupWithResource($resource, $logic);
        
        $this->assertCount(1, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        
    }
    
    /** @test */
    public function withAccessToResource_returns_empty_collection_if_resource_not_one_of_the_three_models(){
        $resource = new class {};

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);
        $this->assertEquals(collect(), $audienceMembers);
    }
    
}
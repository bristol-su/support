<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Tests\TestCase;

class  AudienceFactoryTest extends TestCase
{
    
    /** @test */
    public function fromUser_creates_an_audience_member_from_a_given_user(){
        $user = new User(['id' => 1]);
        $factory = new AudienceMemberFactory;
        $audienceMember = $factory->fromUser($user);

        $this->assertInstanceOf(AudienceMember::class, $audienceMember);
        $this->assertEquals($user, $audienceMember->user());
    }
    
}

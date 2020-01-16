<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Tests\TestCase;

class  AudienceFactoryTest extends TestCase
{
    
    /** @test */
    public function fromUser_creates_an_audience_member_from_a_given_user(){
        $user = $this->newUser(['id' => 1]);
        $factory = new AudienceMemberFactory;
        $audienceMember = $factory->fromUser($user);

        $this->assertInstanceOf(AudienceMember::class, $audienceMember);
        $this->assertEquals($user, $audienceMember->user());
    }
    
}

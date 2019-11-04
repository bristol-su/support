<?php


namespace BristolSU\Support\Tests\DataPlatform\Repositories;


use BristolSU\Support\DataPlatform\Repositories\User;
use BristolSU\Support\Tests\TestCase;
use Twigger\UnionCloud\API\Exception\Resource\ResourceNotFoundException;
use Twigger\UnionCloud\API\Request\UserRequest;
use Twigger\UnionCloud\API\ResourceCollection;
use Twigger\UnionCloud\API\Response\UserResponse;
use Twigger\UnionCloud\API\UnionCloud;

class UserTest extends TestCase
{

    /** @test */
    public function it_gets_a_user_by_email(){
        $email = 'tt15951@bristol.ac.uk';
        $unioncloud = $this->prophesize(UnionCloud::class);
        $userRequest = $this->prophesize(UserRequest::class);
        $userResponse = $this->prophesize(UserResponse::class);
        $resourceCollection = $this->prophesize(ResourceCollection::class);
        $unioncloudUser = $this->prophesize(\Twigger\UnionCloud\API\Resource\User::class);

        $unioncloudUser->getOriginalAttributes()->shouldBeCalled()->willReturn(['email' => $email]);
        $resourceCollection->first()->shouldBeCalled()->willReturn($unioncloudUser->reveal());
        $userResponse->get()->shouldBeCalled()->willReturn($resourceCollection->reveal());
        $userRequest->search(['email' => $email], true)->shouldBeCalled()->willReturn($userResponse->reveal());
        $unioncloud->users()->shouldBeCalled()->willReturn($userRequest->reveal());

        $repository = new User($unioncloud->reveal());
        $user = $repository->getByEmail($email);

        $this->assertInstanceOf(\BristolSU\Support\DataPlatform\Models\User::class, $user);
        $this->assertEquals($email, $user->email());
    }

    /** @test */
    public function it_gets_a_user_by_student_id(){
        $studentId = 'tt15951';
        $unioncloud = $this->prophesize(UnionCloud::class);
        $userRequest = $this->prophesize(UserRequest::class);
        $userResponse = $this->prophesize(UserResponse::class);
        $resourceCollection = $this->prophesize(ResourceCollection::class);
        $unioncloudUser = $this->prophesize(\Twigger\UnionCloud\API\Resource\User::class);

        $unioncloudUser->getOriginalAttributes()->shouldBeCalled()->willReturn(['uid' => 1234567]);
        $resourceCollection->first()->shouldBeCalled()->willReturn($unioncloudUser->reveal());
        $userResponse->get()->shouldBeCalled()->willReturn($resourceCollection->reveal());
        $userRequest->search(['id' => $studentId])->shouldBeCalled()->willReturn($userResponse->reveal());
        $unioncloud->users()->shouldBeCalled()->willReturn($userRequest->reveal());

        $repository = new User($unioncloud->reveal());
        $user = $repository->getByStudentID($studentId);

        $this->assertInstanceOf(\BristolSU\Support\DataPlatform\Models\User::class, $user);
        $this->assertEquals(1234567, $user->id());
    }

    /** @test */
    public function it_checks_a_user_by_email(){
        $email = 'tt15951@bristol.ac.uk';
        $unioncloud = $this->prophesize(UnionCloud::class);
        $userRequest = $this->prophesize(UserRequest::class);
        $userResponse = $this->prophesize(UserResponse::class);
        $resourceCollection = $this->prophesize(ResourceCollection::class);
        $unioncloudUser = $this->prophesize(\Twigger\UnionCloud\API\Resource\User::class);

        $unioncloudUser->getOriginalAttributes()->shouldBeCalled()->willReturn(['email' => $email]);
        $resourceCollection->first()->shouldBeCalled()->willReturn($unioncloudUser->reveal());
        $userResponse->get()->shouldBeCalled()->willReturn($resourceCollection->reveal());
        $userRequest->search(['email' => $email], true)->shouldBeCalled()->willReturn($userResponse->reveal());
        $unioncloud->users()->shouldBeCalled()->willReturn($userRequest->reveal());

        $repository = new User($unioncloud->reveal());
        $user = $repository->getByIdentity($email);

        $this->assertInstanceOf(\BristolSU\Support\DataPlatform\Models\User::class, $user);
        $this->assertEquals($email, $user->email());
    }

    /** @test */
    public function it_checks_a_user_by_student_id_if_email_not_found(){
        $studentId = 'tt15951';
        $unioncloud = $this->prophesize(UnionCloud::class);
        $userRequest = $this->prophesize(UserRequest::class);

        $userResponse = $this->prophesize(UserResponse::class);
        $resourceCollection = $this->prophesize(ResourceCollection::class);
        $unioncloudUser = $this->prophesize(\Twigger\UnionCloud\API\Resource\User::class);

        $unioncloudUser->getOriginalAttributes()->shouldBeCalled()->willReturn(['uid' => 1234567]);
        $resourceCollection->first()->shouldBeCalled()->willReturn($unioncloudUser->reveal());
        $userResponse->get()->shouldBeCalled()->willReturn($resourceCollection->reveal());

        $userRequest->search(['email' => $studentId], true)->shouldBeCalled()->willThrow(new ResourceNotFoundException);
        $userRequest->search(['id' => $studentId])->shouldBeCalled()->willReturn($userResponse->reveal());

        $unioncloud->users()->shouldBeCalledTimes(2)->willReturn($userRequest->reveal());

        $repository = new User($unioncloud->reveal());
        $user = $repository->getByIdentity($studentId);

        $this->assertInstanceOf(\BristolSU\Support\DataPlatform\Models\User::class, $user);
        $this->assertEquals(1234567, $user->id());
    }
    
    /** @test */
    public function it_gets_a_user_by_id(){
        $uid = 12345;
        $unioncloud = $this->prophesize(UnionCloud::class);
        $userRequest = $this->prophesize(UserRequest::class);

        $userResponse = $this->prophesize(UserResponse::class);
        $resourceCollection = $this->prophesize(ResourceCollection::class);
        $unioncloudUser = $this->prophesize(\Twigger\UnionCloud\API\Resource\User::class);

        $unioncloudUser->getOriginalAttributes()->shouldBeCalled()->willReturn(['uid' => $uid]);
        $resourceCollection->first()->shouldBeCalled()->willReturn($unioncloudUser->reveal());
        $userResponse->get()->shouldBeCalled()->willReturn($resourceCollection->reveal());

        $userRequest->getByUID($uid)->shouldBeCalled()->willReturn($userResponse->reveal());

        $unioncloud->users()->shouldBeCalled()->willReturn($userRequest->reveal());

        $repository = new User($unioncloud->reveal());
        $user = $repository->getById($uid);

        $this->assertInstanceOf(\BristolSU\Support\DataPlatform\Models\User::class, $user);
        $this->assertEquals($uid, $user->id());
    }
    
}

<?php

namespace BristolSU\Support\Tests\Authentication\AuthenticationProvider;

use BristolSU\Support\Authentication\AuthenticationProvider\UserProvider;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\TestCase;

class UserProviderTest extends TestCase
{
    /** @test */
    public function retrieve_by_id_retrieves_a_user_by_id(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willReturn(new User(['id' => 1]));

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertEquals(1, $userProvider->retrieveById(1)->id);
    }

    /** @test */
    public function retrieve_by_credentials_retrieves_a_user_by_credentials(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willReturn(new User(['id' => 1]));

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertEquals(1, $userProvider->retrieveByCredentials(['user_id' => 1])->id);
    }

    /** @test */
    public function retrieve_by_credentials_returns_null_if_user_id_not_set(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertNull($userProvider->retrieveByCredentials([]));
    }

    /** @test */
    public function validate_credentials_returns_true_if_user_id_valid(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willReturn(new User(['id' => 1]));
        $user = new User();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertTrue($userProvider->validateCredentials($user, ['user_id' => 1]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_user_id_not_found(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception);
        $user = new User();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertFalse($userProvider->validateCredentials($user, ['user_id' => 1]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_user_id_not_given(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $user = new User();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertFalse($userProvider->validateCredentials($user, []));
    }
}
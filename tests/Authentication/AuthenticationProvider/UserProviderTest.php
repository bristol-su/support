<?php

namespace BristolSU\Support\Tests\Authentication\AuthenticationProvider;

use BristolSU\Support\Authentication\AuthenticationProvider\UserProvider;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Tests\TestCase;

class UserProviderTest extends TestCase
{
    /** @test */
    public function retrieve_by_id_retrieves_a_user_by_id(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willReturn($this->newUser(['id' => 1]));

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertEquals(1, $userProvider->retrieveById(1)->id);
    }

    /** @test */
    public function retrieve_by_credentials_retrieves_a_user_by_credentials(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willReturn($this->newUser(['id' => 1]));

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
        $userRepository->getById(1)->shouldBeCalled()->willReturn($this->newUser(['id' => 1]));
        $user = $this->newUser();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertTrue($userProvider->validateCredentials($user, ['user_id' => 1]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_user_id_not_found(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception);
        $user = $this->newUser();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertFalse($userProvider->validateCredentials($user, ['user_id' => 1]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_user_id_not_given(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $user = $this->newUser();

        $userProvider = new UserProvider($userRepository->reveal());
        $this->assertFalse($userProvider->validateCredentials($user, []));
    }

    /** @test */
    public function retrieveByToken_always_returns_null(){
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userProvider = new UserProvider($userRepository->reveal());

        $this->assertNull($userProvider->retrieveByToken(1, 'fff'));

    }

    /** @test */
    public function updateRememberToken_always_returns_null(){
        $user = $this->newUser();
        $userRepository = $this->prophesize(UserRepositoryContract::class);
        $userProvider = new UserProvider($userRepository->reveal());

        $this->assertNull($userProvider->updateRememberToken($user, 'fff'));
    }
}
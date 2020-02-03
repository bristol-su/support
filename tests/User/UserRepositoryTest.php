<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\User\UserRepository;
use BristolSU\Support\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use BristolSU\Support\Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    
   
    /** @test */
    public function getFromControlId_gets_the_user_with_the_control_id()
    {
        $user = factory(User::class)->create();

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getFromControlId($user->control_id);
        $this->assertInstanceOf(User::class, $resolvedUser);
        $this->assertModelEquals($user, $resolvedUser);
    }

    /** @test */
    public function getFromControlId_throws_an_exception_if_no_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getFromControlId(6);
    }

    /** @test */
    public function create_creates_a_user()
    {
        $userParams = [
            'control_id' => 1,
            'auth_provider' => 'facebook',
            'auth_provider_id' => 5000
        ];

        $userRepository = new UserRepository;
        $user = $userRepository->create($userParams);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->controlId());
        $this->assertEquals('facebook', $user->auth_provider);
        $this->assertEquals(5000, $user->auth_provider_id);

        $this->assertDatabaseHas('users', $userParams);
    }

    /** @test */
    public function all_gets_all_users()
    {
        $users = factory(User::class, 10)->create();

        $userRepository = new UserRepository;
        $allUsers = $userRepository->all();

        foreach($users as $user) {
            $this->assertModelEquals($user, $allUsers->shift());
        }
    }

    /** @test */
    public function getWhereEmail_throws_an_exception_if_no_data_user_found(){
        $this->expectException(ModelNotFoundException::class);

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function getWhereEmail_throws_an_exception_if_no_control_user_found(){
        $this->expectException(ModelNotFoundException::class);

        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function getWhereEmail_throws_an_exception_if_no_user_found(){
        $this->expectException(ModelNotFoundException::class);

        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function getWhereEmail_returns_a_user_with_a_given_email(){
        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $userRepository = new UserRepository;
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
        $this->assertModelEquals($user, $resolvedUser);
    }
}

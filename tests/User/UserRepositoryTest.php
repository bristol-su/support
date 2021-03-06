<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use BristolSU\Support\User\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepositoryTest extends TestCase
{
    /** @test */
    public function get_from_control_id_gets_the_user_with_the_control_id()
    {
        $user = factory(User::class)->create();

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getFromControlId($user->control_id);
        $this->assertInstanceOf(User::class, $resolvedUser);
        $this->assertModelEquals($user, $resolvedUser);
    }

    /** @test */
    public function get_from_control_id_throws_an_exception_if_no_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getFromControlId(6);
    }

    /** @test */
    public function create_creates_a_user()
    {
        $userParams = [
            'control_id' => 1,
        ];

        $userRepository = new UserRepository();
        $user = $userRepository->create($userParams);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->controlId());

        $this->assertDatabaseHas('users', $userParams);
    }

    /** @test */
    public function all_gets_all_users()
    {
        $users = factory(User::class, 10)->create();

        $userRepository = new UserRepository();
        $allUsers = $userRepository->all();

        foreach ($users as $user) {
            $this->assertModelEquals($user, $allUsers->shift());
        }
    }

    /** @test */
    public function get_where_email_throws_an_exception_if_no_data_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function get_where_email_throws_an_exception_if_no_control_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function get_where_email_throws_an_exception_if_no_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
    }

    /** @test */
    public function get_where_email_returns_a_user_with_a_given_email()
    {
        $dataUser = factory(DataUser::class)->create(['email' => 'tobytwigger@example.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getWhereEmail('tobytwigger@example.com');
        $this->assertModelEquals($user, $resolvedUser);
    }

    /** @test */
    public function get_from_remember_token_gets_the_user_with_the_control_id()
    {
        $user = factory(User::class)->create(['remember_token' => 'abc123']);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getFromRememberToken('abc123');
        $this->assertInstanceOf(User::class, $resolvedUser);
        $this->assertModelEquals($user, $resolvedUser);
    }

    /** @test */
    public function get_from_remember_token_throws_an_exception_if_no_user_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getFromrememberToken('abc1234');
    }
    
    /** @test */
    public function get_by_id_returns_a_user_by_id()
    {
        $user = factory(User::class)->create(['id' => 1]);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->getById(1);
        $this->assertInstanceOf(User::class, $resolvedUser);
        $this->assertModelEquals($user, $resolvedUser);
    }

    /** @test */
    public function set_remember_token_sets_the_remember_token_of_the_user()
    {
        $user = factory(User::class)->create(['remember_token' => 'abc123']);

        $userRepository = new UserRepository();
        $resolvedUser = $userRepository->setRememberToken($user->id, 'abc1234');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id, 'remember_token' => 'abc1234'
        ]);
    }
}

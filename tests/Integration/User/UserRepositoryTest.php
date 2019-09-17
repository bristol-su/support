<?php

namespace BristolSU\Support\Tests\Integration\User;

use BristolSU\Support\User\UserRepository;
use BristolSU\Support\User\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use BristolSU\Support\Testing\TestCase;

class UserRepositoryTest extends TestCase
{


    public function getWhereEmail($email)
    {
        return User::where('email', $email)->get();
    }

    public function create(array $attributes)
    {
        return User::create($attributes);
    }

    public function all()
    {
        return User::all();
    }

    /** @test */
    public function getWhereIdentity_gets_a_user_by_email()
    {
        $user = factory(User::class)->create();

        $userRepository = new UserRepository;
        $this->assertModelEquals($user,
            $userRepository->getWhereIdentity($user->email)
        );
    }

    /** @test */
    public function getWhereIdentity_gets_a_user_by_student_id()
    {
        $user = factory(User::class)->create();

        $userRepository = new UserRepository;
        $this->assertModelEquals($user,
            $userRepository->getWhereIdentity($user->student_id)
        );
    }

    /** @test */
    public function getWhereEmail_gets_all_users_with_given_email()
    {
        $user = factory(User::class)->create();

        $userRepository = new UserRepository;
        $users = $userRepository->getWhereEmail($user->email);
        $this->assertInstanceOf(Collection::class, $users);
        $this->assertModelEquals($user, $users->first());
    }

    /** @test */
    public function create_creates_a_user()
    {
        $userParams = [
            'forename' => 'firstname',
            'surname' => 'lastname',
            'email' => 'email',
        ];

        $userRepository = new UserRepository;
        $user = $userRepository->create($userParams);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('firstname', $user->forename);

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
}

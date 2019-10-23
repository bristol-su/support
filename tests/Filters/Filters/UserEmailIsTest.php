<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\DataPlatform\Contracts\Repositories\User as DataUserRepository;
use BristolSU\Support\DataPlatform\Models\User as DataUser;
use BristolSU\Support\Filters\Filters\UserEmailIs;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\TestCase;

class UserEmailIsTest extends TestCase
{

    /** @test */
    public function options_returns_a_blank_string_for_email(){
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $this->prophesize(DataUserRepository::class)->reveal());

        $this->assertEquals(['email' => ''], $filter->options());
    }

    /** @test */
    public function evaluate_returns_true_if_a_user_email_is_equal_to_the_settings(){
        $user = new User(['id' => 10, 'uc_uid' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn(new DataUser(['id' => 1, 'email' => 'tt15951@example.com']));
        
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $dataUserRepository->reveal());
        $filter->setModel($user);
        $this->assertTrue($filter->evaluate(['email' => 'tt15951@example.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_a_user_email_is_not_equal_to_the_settings(){
        $user = new User(['id' => 10, 'uc_uid' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn(new DataUser(['id' => 1, 'email' => 'tt15951@example.com']));
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $dataUserRepository->reveal());
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tt15951@notexample.com']));
    }

    /** @test */
    public function name_returns_the_filter_name(){
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $this->prophesize(DataUserRepository::class)->reveal());

        $this->assertEquals('User has Email', $filter->name());
    }

    /** @test */
    public function description_returns_the_filter_description(){
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $this->prophesize(DataUserRepository::class)->reveal());

        $this->assertEquals('User has a given email address', $filter->description());
    }

    /** @test */
    public function alias_returns_the_filter_alias(){
        $filter = new UserEmailIs($this->prophesize(UserRepository::class)->reveal(), $this->prophesize(DataUserRepository::class)->reveal());

        $this->assertEquals('user_email_is', $filter->alias());
    }

    /** @test */
    public function audience_gets_all_users_with_the_given_email(){
        $dataUser = new DataUser(['uid' => 5]);
        $controlUser = new User(['id' => 2]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getByEmail('email1@email.com')->shouldBeCalled()->willReturn($dataUser);
        
        $controlUserRepository = $this->prophesize(UserRepository::class);
        $controlUserRepository->findByDataId(5)->shouldBeCalled()->willReturn($controlUser);

        $filter = new UserEmailIs($controlUserRepository->reveal(), $dataUserRepository->reveal());
        $this->assertEquals($controlUser, $filter->audience(['email' => 'email1@email.com'])->first());

        
    }

}

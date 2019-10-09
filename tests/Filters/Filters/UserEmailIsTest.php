<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Filters\UserEmailIs;
use BristolSU\Support\User\Contracts\UserRepository;
use BristolSU\Support\User\User;
use BristolSU\Support\Tests\TestCase;

class UserEmailIsTest extends TestCase
{

    /** @test */
    public function options_returns_a_blank_string_for_email(){
        $authentication = $this->prophesize(Authentication::class);
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertEquals(['email' => ''], $filter->options());
    }

    /** @test */
    public function evaluate_returns_true_if_a_user_email_is_equal_to_the_settings(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(new User(['email' => 'tt15951@example.com']));
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertTrue($filter->evaluate(['email' => 'tt15951@example.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_a_user_email_is_not_equal_to_the_settings(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(new User(['email' => 'tt15951@example.com']));
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertFalse($filter->evaluate(['email' => 'tt15951@nothesame.com']));
    }

    /** @test */
    public function name_returns_the_filter_name(){
        $authentication = $this->prophesize(Authentication::class);
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertEquals('User has Email', $filter->name());
    }

    /** @test */
    public function description_returns_the_filter_description(){
        $authentication = $this->prophesize(Authentication::class);
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertEquals('User has a given email address', $filter->description());
    }

    /** @test */
    public function alias_returns_the_filter_alias(){
        $authentication = $this->prophesize(Authentication::class);
        $filter = new UserEmailIs($authentication->reveal(), $this->prophesize(UserRepository::class)->reveal());

        $this->assertEquals('user_email', $filter->alias());
    }

    /** @test */
    public function has_model_returns_true_if_get_user_not_null(){
        $auth = $this->prophesize(Authentication::class);
        $auth->getUser()->shouldBeCalled()->willReturn(new \BristolSU\Support\Control\Models\User);

        $userEmailFilter = new UserEmailIs($auth->reveal(), $this->prophesize(UserRepository::class)->reveal());
        $this->assertTrue($userEmailFilter->hasModel());
    }


    /** @test */
    public function has_model_returns_false_if_get_user_null(){
        $auth = $this->prophesize(Authentication::class);
        $auth->getUser()->shouldBeCalled()->willReturn(null);

        $userEmailFilter = new UserEmailIs($auth->reveal(), $this->prophesize(UserRepository::class)->reveal());
        $this->assertFalse($userEmailFilter->hasModel());
    }

    /** @test */
    public function audience_gets_all_users_with_the_given_email(){
        $user = factory(User::class)->create();
        $auth = $this->prophesize(Authentication::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getWhereEmail('email1@email.com')->shouldBeCalled()->willReturn(collect([$user]));
        $filter = new UserEmailIs($auth->reveal(), $userRepository->reveal());
        $this->assertModelEquals($user, $filter->audience(['email' => 'email1@email.com'])->first());
    }

}

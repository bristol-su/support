<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\ControlDB\Contracts\Repositories\DataUser as DataUserRepository;
use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Tests\TestCase;

class UserEmailIsTest extends TestCase
{

    /** @test */
    public function options_returns_a_blank_string_for_email(){
        $filter = new UserEmailIs();

        $this->assertEquals(['email' => ''], $filter->options());
    }

    /** @test */
    public function evaluate_returns_true_if_a_user_email_is_equal_to_the_settings(){
        $dataUser = factory(DataUser::class)->create(['id' => 1, 'email' => 'tobyt@example.com']);
        $user = $this->newUser(['id' => 10, 'data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn($dataUser);
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());
        
        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertTrue($filter->evaluate(['email' => 'tobyt@example.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_a_user_email_is_not_equal_to_the_settings(){
        $dataUser = factory(DataUser::class)->create(['id' => 1, 'email' => 'tobyt@example.com']);
        $user = $this->newUser(['id' => 10, 'data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn($dataUser);
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());

        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tobyt@notexample.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_dataRepository_throws_exception(){
        $user = $this->newUser(['id' => 10, 'data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());

        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tobyt@notexample.com']));
    }




}

<?php


namespace BristolSU\Support\Tests\Filters\Filters;

use BristolSU\ControlDB\Contracts\Repositories\DataUser as DataUserRepository;
use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Tests\TestCase;

class UserEmailIsTest extends TestCase
{
    /** @test */
    public function options_returns_a_blank_string_for_email()
    {
        $filter = new UserEmailIs();
        
        $this->assertEquals(1, count($filter->options()->fields()));
        $this->assertEquals('email', $filter->options()->fields()[0]->model());
        $this->assertEquals('email', $filter->options()->fields()[0]->inputType());
    }

    /** @test */
    public function evaluate_returns_true_if_a_user_email_is_equal_to_the_settings()
    {
        $dataUser = factory(DataUser::class)->create(['id' => 1, 'email' => 'tobyt@example.com']);
        $user = $this->newUser(['data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn($dataUser);
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());
        
        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertTrue($filter->evaluate(['email' => 'tobyt@example.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_a_user_email_is_not_equal_to_the_settings()
    {
        $dataUser = factory(DataUser::class)->create(['id' => 1, 'email' => 'tobyt@example.com']);
        $user = $this->newUser(['data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn($dataUser);
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());

        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tobyt@notexample.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_data_repository_throws_exception()
    {
        $user = $this->newUser(['data_provider_id' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());
        $this->app->instance(DataUserRepository::class, $dataUserRepository->reveal());

        $filter = new UserEmailIs();
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tobyt@notexample.com']));
    }

    /** @test */
    public function name_returns_a_string()
    {
        $filter = new UserEmailIs();
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $filter = new UserEmailIs();
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $filter = new UserEmailIs();
        $this->assertIsString($filter->alias());
    }
}

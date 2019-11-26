<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\Support\DataPlatform\Contracts\Repositories\User as DataUserRepository;
use BristolSU\Support\DataPlatform\Models\User as DataUser;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\TestCase;

class UserEmailIsTest extends TestCase
{

    /** @test */
    public function options_returns_a_blank_string_for_email(){
        $filter = new UserEmailIs($this->prophesize(DataUserRepository::class)->reveal());

        $this->assertEquals(['email' => ''], $filter->options());
    }

    /** @test */
    public function evaluate_returns_true_if_a_user_email_is_equal_to_the_settings(){
        $user = new User(['id' => 10, 'uc_uid' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn(new DataUser(['id' => 1, 'email' => 'tt15951@example.com']));
        
        $filter = new UserEmailIs($dataUserRepository->reveal());
        $filter->setModel($user);
        $this->assertTrue($filter->evaluate(['email' => 'tt15951@example.com']));
    }

    /** @test */
    public function evaluate_returns_false_if_a_user_email_is_not_equal_to_the_settings(){
        $user = new User(['id' => 10, 'uc_uid' => 1]);
        $dataUserRepository = $this->prophesize(DataUserRepository::class);
        $dataUserRepository->getById(1)->shouldBeCalled()->willReturn(new DataUser(['id' => 1, 'email' => 'tt15951@example.com']));
        $filter = new UserEmailIs($dataUserRepository->reveal());
        $filter->setModel($user);
        $this->assertFalse($filter->evaluate(['email' => 'tt15951@notexample.com']));
    }




}

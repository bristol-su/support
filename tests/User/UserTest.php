<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Validation\ValidationException;

class UserTest extends TestCase
{
    /** @test */
    public function control_id_returns_the_control_id()
    {
        $user = factory(User::class)->create(['id' => 1]);

        $this->assertEquals(1, $user->controlId());
    }

    /** @test */
    public function control_user_returns_the_control_user()
    {
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['id' => 1]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $user->controlUser());
        $this->assertModelEquals($controlUser, $user->controlUser());
    }

    /** @test */
    public function route_notification_for_mail_returns_the_user_email()
    {
        $dataUser = factory(DataUser::class)->create(['email' => 'example@test.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $this->assertEquals('example@test.com', $user->routeNotificationForMail());
    }

    /** @test */
    public function get_email_for_verification_returns_the_user_email()
    {
        $dataUser = factory(DataUser::class)->create(['email' => 'example@test.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $this->assertEquals('example@test.com', $user->getEmailForVerification());
    }

    /** @test */
    public function get_email_for_verification_throws_a_validation_exception_if_no_email_found()
    {
        $this->expectException(ValidationException::class);

        $dataUser = factory(DataUser::class)->create(['email' => null]);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $user->getEmailForVerification();
    }

    /** @test */
    public function get_email_for_password_reset_returns_the_user_email()
    {
        $dataUser = factory(DataUser::class)->create(['email' => 'example@test.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $this->assertEquals('example@test.com', $user->getEmailForPasswordReset());
    }

    /** @test */
    public function get_email_for_password_reset_throws_a_validation_exception_if_no_email_found()
    {
        $this->expectException(ValidationException::class);

        $dataUser = factory(DataUser::class)->create(['email' => null]);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $user->getEmailForPasswordReset();
    }

    /** @test */
    public function find_for_passport_returns_the_data_user_model_from_an_email_address()
    {
        $email = 'test@example.com';
        $dataUser = factory(DataUser::class)->create(['email' => $email]);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $dbUser = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $foundUser = (new User())->findForPassport($email);
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertModelEquals($dbUser, $foundUser);
    }

    /** @test */
    public function find_for_passport_returns_null_if_the_email_address_does_not_exist()
    {
        $email = 'test@example.com';

        $foundUser = (new User())->findForPassport($email);
        $this->assertNull($foundUser);
    }

    /** @test */
    public function find_for_passport_returns_null_if_the_control_user_does_not_exist()
    {
        $email = 'test@example.com';
        $dataUser = factory(DataUser::class)->create(['email' => $email]);

        $foundUser = (new User())->findForPassport($email);
        $this->assertNull($foundUser);
    }

    /** @test */
    public function find_for_passport_returns_null_if_the_database_user_does_not_exist()
    {
        $email = 'test@example.com';
        $dataUser = factory(DataUser::class)->create(['email' => $email]);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);


        $foundUser = (new User())->findForPassport($email);
        $this->assertNull($foundUser);
    }
}

<?php

namespace BristolSU\Support\User;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\HasApiTokens;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Represents a user in the database
 */
class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable, HasApiTokens, MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'control_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the ID of the control
     * 
     * @return int
     */
    public function controlId(): int
    {
        return (int) $this->control_id;
    }

    /**
     * Get the control user attached to this database user
     * 
     * @return \BristolSU\ControlDB\Contracts\Models\User
     */
    public function controlUser()
    {
        return app(\BristolSU\ControlDB\Contracts\Repositories\User::class)->getById($this->controlId());
    }

    /**
     * Get the user email to send a notification to
     * 
     * @return string|null
     */
    public function routeNotificationForMail()
    {
        return $this->controlUser()->data()->email();
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     * @throws ValidationException
     */
    public function getEmailForVerification()
    {
        $email = $this->controlUser()->data()->email();
        if(!$email) {
            throw ValidationException::withMessages([
                'identifier' => 'Your email has not been set.'
            ]);
        }
        return $email;
    }
}

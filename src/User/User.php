<?php

namespace BristolSU\Support\User;

use BristolSU\ControlDB\Contracts\Repositories\DataUser;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\HasApiTokens;

/**
 * Represents a user in the database.
 */
class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable;
    use HasApiTokens;

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
     * Get the ID of the control.
     *
     * @return int
     */
    public function controlId(): int
    {
        return (int) $this->control_id;
    }

    /**
     * Get the control user attached to this database user.
     *
     * @return \BristolSU\ControlDB\Contracts\Models\User
     */
    public function controlUser()
    {
        return app(\BristolSU\ControlDB\Contracts\Repositories\User::class)->getById($this->controlId());
    }

    /**
     * Get the user email to send a notification to.
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
     * @throws ValidationException
     * @return string
     */
    public function getEmailForVerification()
    {
        $email = $this->controlUser()->data()->email();
        if (!$email) {
            throw ValidationException::withMessages([
                'identifier' => 'Your email has not been set.'
            ]);
        }

        return $email;
    }

    /**
     * Get the email address that should be used for password resets.
     *
     * @throws ValidationException
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        $email = $this->controlUser()->data()->email();
        if (!$email) {
            throw ValidationException::withMessages([
                'identifier' => 'Your email has not been set.'
            ]);
        }

        return $email;
    }

    public function findForPassport(string $email)
    {
        try {
            $dataUser = app(DataUser::class)->getWhere(['email' => $email]);
        } catch (ModelNotFoundException $e) {
            return null;
        }
        $controlUser = $dataUser->user();
        if ($controlUser === null) {
            return null;
        }

        return static::newQuery()->where('control_id', $controlUser->id())->first();
    }
}

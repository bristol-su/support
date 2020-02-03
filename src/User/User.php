<?php

namespace BristolSU\Support\User;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
        'control_id', 'auth_provider', 'auth_provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'auth_provider', 'auth_provider_id'
    ];

    public function controlId()
    {
        return $this->control_id;
    }

    public function controlUser()
    {
        return app(\BristolSU\ControlDB\Contracts\Repositories\User::class)->getById($this->controlId());
    }
}

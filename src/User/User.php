<?php

namespace BristolSU\Support\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'forename', 'surname', 'email', 'student_id', 'control_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // TODO Implement below mailing
//    public function sendPasswordResetNotification($token)
//    {
//        Mail::to($this->email)->send(new ResetPasswordMail($token));
//    }
//
//    /**
//     * Send the email verification notification.
//     *
//     * @return void
//     */
//    public function sendEmailVerificationNotification()
//    {
//        Mail::to($this->email)->send(new VerifyEmailMail($this));
//    }
}

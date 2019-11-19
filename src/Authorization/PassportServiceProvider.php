<?php


namespace BristolSU\Support\Authorization;


use BristolSU\Support\User\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as ServiceProvider;

/**
 * Class PassportServiceProvider
 * 
 * Overridden service provider for Laravel passport to allow for the logout() method to be used without
 * clearing user tokens.
 * 
 * @package BristolSU\Support\Authorization
 */
class PassportServiceProvider extends ServiceProvider
{

    protected function deleteCookieOnLogout()
    {
        Event::listen(Logout::class, function ($event) {
            if (Request::hasCookie(Passport::cookie()) && $event->user instanceof User) {
                Cookie::queue(Cookie::forget(Passport::cookie()));
            }
        });
    }

}
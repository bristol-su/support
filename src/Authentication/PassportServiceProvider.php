<?php


namespace BristolSU\Support\Authentication;

use BristolSU\Support\User\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as ServiceProvider;

/**
 * Class PassportServiceProvider.
 *
 * Overridden service provider for Laravel passport to allow for the logout() method to be used without
 * clearing user tokens.
 *
 */
class PassportServiceProvider extends ServiceProvider
{
    /**
     * Stop the api authentication cookie being logged out when any model is logged out of.
     *
     * Since the package uses the same framework for users, groups, roles and activity instances, we need to ensure
     * we only log out of the api if we log out of the database user.
     */
    protected function deleteCookieOnLogout()
    {
        Event::listen(Logout::class, function ($event) {
            if (Request::hasCookie(Passport::cookie()) && $event->user instanceof User) {
                Cookie::queue(Cookie::forget(Passport::cookie()));
            }
        });
    }
}

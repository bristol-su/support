<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

/**
 * Authentication Service Provider
 */
class AuthenticationServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Bind the resource ID generator
     * - Bind the authentication contract
     * - Register the passport service provider. The normal service provider should be disabled.
     */
    public function register()
    {
        $this->app->call([$this, 'registerAuthentication']);
        $this->app->bind(ResourceIdGenerator::class, AuthenticationResourceIdGenerator::class);
        $this->app->register(PassportServiceProvider::class);
    }

    /**
     * Boot
     */
    public function boot()
    {
        UrlGenerator::macro('getAuthQueryArray', function() {
            $query = [];
            if(app(Authentication::class)->getUser() !== null) {
                $query['u'] = app(Authentication::class)->getUser()->id();
            }
            if(app(Authentication::class)->getGroup() !== null) {
                $query['g'] = app(Authentication::class)->getGroup()->id();
            }
            if(app(Authentication::class)->getRole() !== null) {
                $query['r'] = app(Authentication::class)->getRole()->id();
            }
            try {
                $query['a'] = app(ActivityInstanceResolver::class)->getActivityInstance()->id;
            } catch (NotInActivityInstanceException $e) {}
            return $query;
        });
        UrlGenerator::macro('getAuthQueryString', function() {
            return http_build_query(UrlGenerator::getAuthQueryArray(), '', '&', PHP_QUERY_RFC3986);
        });
    }

    public function registerAuthentication(Request $request)
    {
        $this->app->bind(Authentication::class, function($app) use ($request) {
            return ($request->is('api/*') ?
                $app->make(ApiAuthentication::class) : $app->make(WebRequestAuthentication::class));
        });

    }
    

}
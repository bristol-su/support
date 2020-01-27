<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    }

    public function registerAuthentication(Request $request)
    {
        $this->app->bind(Authentication::class, function($app) use ($request) {
            return ($request->is('api/*') ?
                $app->make(ApiAuthentication::class) : $app->make(WebSessionAuthentication::class));
        });

    }
    

}
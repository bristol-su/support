<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Contracts\Foundation\Application;

/**
 * Handle the necessary setup to use the sdk test tools
 */
trait CreatesSdkEnvironment
{

    /**
     * Handle the necessary setup to use the sdk test tools
     * 
     * @param Application $app
     */
    public function createSdkEnvironment(Application $app)
    {
        $app['config']->set('auth.guards.role', [
            'driver' => 'session',
            'provider' => 'roles'
        ]);
        $app['config']->set('auth.guards.group', [
            'driver' => 'session',
            'provider' => 'groups'
        ]);
        $app['config']->set('auth.guards.user', [
            'driver' => 'session',
            'provider' => 'users'
        ]);
        $app['config']->set('auth.guards.activity-instance', [
            'driver' => 'session',
            'provider' => 'activity-instances'
        ]);
        $app['config']->set('auth.providers.roles', [
            'driver' => 'role-provider',
            'model' => Role::class
        ]);
        $app['config']->set('auth.providers.users', [
            'driver' => 'user-provider',
            'model' => User::class
        ]);
        $app['config']->set('auth.providers.groups', [
            'driver' => 'group-provider',
            'model' => Group::class
        ]);
        $app['config']->set('auth.providers.activity-instances', [
            'driver' => 'activity-instance-provider',
            'model' => ActivityInstance::class
        ]);
    }
    
}
<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Contracts\Foundation\Application;

/**
 * Trait to handle the necessary setup to use the sdk test tools
 */
trait CreatesSdkEnvironment
{

    /**
     * Handle the necessary setup to use the sdk test tools
     * 
     * - Set up authentication providers for user, group, role and activity instance
     * 
     * @param Application $app
     */
    public function createSdkEnvironment(Application $app)
    {
        $app['config']->set('auth.guards.activity-instance', [
            'driver' => 'session',
            'provider' => 'activity-instances'
        ]);
        $app['config']->set('auth.providers.activity-instances', [
            'driver' => 'activity-instance-provider',
            'model' => ActivityInstance::class
        ]);
    }
    
}
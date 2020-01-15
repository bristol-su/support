<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\User\User as DatabaseUser;
use Illuminate\Support\Str;

/**
 * Set up a module testing environment
 */
trait CreatesModuleEnvironment
{

    public function createModuleEnvironment()
    {
        $this->activity = factory(Activity::class)->create(['slug' => 'act']);
        $this->moduleInstance = factory(ModuleInstance::class)->create(['slug' => 'mod', 'activity_id' => $this->activity->id, 'alias' => $this->alias()]);
        $this->databaseUser = factory(DatabaseUser::class)->create();
        $this->activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $this->activity->id, 'resource_id' => $this->databaseUser->control_id, 'resource_type' => 'user']);
        $this->user = new User(['id' => $this->databaseUser->control_id]);
        $this->group = new Group(['id' => 3]);
        $this->role = new Role(['id' => 5]);
        $this->app->instance(Activity::class, $this->activity);
        $this->app->instance(ActivityInstance::class, $this->activityInstance);
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn($this->activityInstance);
        $this->app->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());
        $this->app->instance(ModuleInstance::class, $this->moduleInstance);
    }

    /**
     * @param string $path
     * @return string
     */
    public function adminUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/a/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias() . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function userUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/p/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias() . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function apiUrl($path = '', $admin = false)
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/api/' . ($admin?'a':'p') . '/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias . $path;
    }


}
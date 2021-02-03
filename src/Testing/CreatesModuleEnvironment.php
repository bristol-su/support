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
 * Trait to help set up a module testing environment.
 */
trait CreatesModuleEnvironment
{
    use HandlesAuthentication, HandlesAuthorization;

    /**
     * Alias of the module to set up.
     *
     * @var string
     */
    private $alias;

    /**
     * Activity with the module in.
     *
     * @var null|Activity
     */
    private $activity;

    /**
     * Module instance for the module.
     * @var null|ModuleInstance
     */
    private $moduleInstance;

    /**
     * Activity instance, related to the current authentication information.
     *
     * @var null|ActivityInstance
     */
    private $activityInstance;

    /**
     * Who should the activity be for?
     *
     * Should be one of user, group or role
     *
     * @var string
     */
    private $for;

    /**
     * Stores the user being used.
     *
     * @var User|null
     */
    private $controlUser;

    /**
     * Stores the group being used.
     *
     * @var Group|null
     */
    private $controlGroup;

    /**
     * Stores the role being used.
     *
     * @var Role|null
     */
    private $controlRole;

    /**
     * Stores the database user being used.
     *
     * @var DatabaseUser|null
     */
    private $databaseUser;

    /**
     * Set up the module in the set configuration.
     *
     * @param string $alias Alias of the module to set up
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createModuleEnvironment(string $alias)
    {
        $this->alias = $alias;
        $this->setUpAuthentication();
        $this->setUpModule();
        $this->setUpDatabaseUser();
    }

    /**
     * Set the activity to stage the module in.
     *
     * @param Activity $activity
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Set the module instance to stage the module in.
     *
     * @param \BristolSU\Support\ModuleInstance\Contracts\ModuleInstance $moduleInstance
     */
    public function setModuleInstance(\BristolSU\Support\ModuleInstance\Contracts\ModuleInstance $moduleInstance)
    {
        $this->moduleInstance = $moduleInstance;
    }

    /**
     * Set who the module is for. Must be one of user, group or role.
     *
     * @param string $for
     */
    public function setModuleIsFor(string $for)
    {
        $this->for = $for;
    }

    /**
     * Set the activity instance to use.
     *
     * @param ActivityInstance $activityInstance
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        $this->activityInstance = $activityInstance;
    }

    /**
     * Set the control user to use.
     *
     * @param \BristolSU\ControlDB\Contracts\Models\User $user
     */
    public function setControlUser(\BristolSU\ControlDB\Contracts\Models\User $user)
    {
        $this->controlUser = $user;
    }

    /**
     * Set the control group to use.
     *
     * @param \BristolSU\ControlDB\Contracts\Models\Group $group
     */
    public function setControlGroup(\BristolSU\ControlDB\Contracts\Models\Group $group)
    {
        $this->controlGroup = $group;
    }

    /**
     * Set the control role to use
     * +.
     * @param \BristolSU\ControlDB\Contracts\Models\Role $role
     */
    public function setControlRole(\BristolSU\ControlDB\Contracts\Models\Role $role)
    {
        $this->controlRole = $role;
    }

    /**
     * Set the database user.
     *
     * @param DatabaseUser $databaseUser
     */
    public function setDatabaseUser(DatabaseUser $databaseUser)
    {
        $this->databaseUser = $databaseUser;
    }

    /**
     * Get the activity to stage the module in.
     *
     * @return Activity
     */
    public function getActivity(): Activity
    {
        return $this->activity;
    }

    /**
     * Get who the module is for. One of user, group or role.
     *
     * @return string
     */
    public function getModuleIsFor(): string
    {
        return $this->for;
    }
    
    /**
     * Get the module instance to stage the module in.
     *
     * @return \BristolSU\Support\ModuleInstance\Contracts\ModuleInstance
     */
    public function getModuleInstance(): \BristolSU\Support\ModuleInstance\Contracts\ModuleInstance
    {
        return $this->moduleInstance;
    }

    /**
     * Get the activity instance to use.
     *
     * @return ActivityInstance
     */
    public function getActivityInstance(): ActivityInstance
    {
        return $this->activityInstance;
    }

    /**
     * Get the control user to use.
     *
     * @return \BristolSU\ControlDB\Contracts\Models\User
     */
    public function getControlUser(): \BristolSU\ControlDB\Contracts\Models\User
    {
        return $this->controlUser;
    }

    /**
     * Get the control group to use.
     *
     * @return \BristolSU\ControlDB\Contracts\Models\Group
     */
    public function getControlGroup(): \BristolSU\ControlDB\Contracts\Models\Group
    {
        return $this->controlGroup;
    }

    /**
     * Get the control role to use.
     *
     * @return \BristolSU\ControlDB\Contracts\Models\Role
     */
    public function getControlRole(): \BristolSU\ControlDB\Contracts\Models\Role
    {
        return $this->controlRole;
    }

    /**
     * Get the database user.
     *
     * @return DatabaseUser
     */
    public function getDatabaseUser(): DatabaseUser
    {
        return $this->databaseUser;
    }
    
    /**
     * Set up the module.
     *
     * - Get/create the activity and bind it
     * - Get/create a module instance and bind
     * - Get/create an activity instance and bind
     */
    private function setUpModule()
    {
        $this->activity = ($this->activity
            ?? factory(Activity::class)->create([
                'slug' => Str::random(5),
                'activity_for' => ($this->for??'user')
            ]));
        
        $this->moduleInstance = ($this->moduleInstance
            ?? factory(ModuleInstance::class)->create([
                'slug' => Str::random(5),
                'activity_id' => $this->activity->id,
                'alias' => $this->alias
            ]));
        
        $this->activityInstance = ($this->activityInstance
            ?? factory(ActivityInstance::class)->create([
                'activity_id' => $this->activity->id,
                'resource_id' => (
                    $this->for === 'role' ? $this->controlRole->id() : ($this->for === 'group' ? $this->controlGroup->id() : $this->controlUser->id())
                ),
                'resource_type' => ($this->for??'user')
            ]));

        app()->instance(Activity::class, $this->activity);
        app()->instance(ModuleInstance::class, $this->moduleInstance);
        app()->make(ActivityInstanceResolver::class)->setActivityInstance($this->activityInstance);
        app()->instance(ActivityInstance::class, $this->activityInstance);
    }

    /**
     * Set up the control authentication.
     *
     * Sets the user/group/role that is able to use the module for a given 'for' value.
     * - if 'for' is 'role', will create and log in a user, group and role
     * - if 'for' is 'group', will create and log in a user and group
     * - if 'for' is 'user', will create and log in a user
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function setUpAuthentication()
    {
        if ($this->for === 'group') {
            $this->controlGroup = ($this->controlGroup ?? $this->newGroup());
            $this->beGroup($this->controlGroup);
        }
        if ($this->for === 'role') {
            $this->controlRole = ($this->controlRole ?? $this->newRole());
            $this->controlGroup = $this->controlRole->group();
            $this->beRole($this->controlRole);
            $this->beGroup($this->controlGroup);
        }
        $this->controlUser = ($this->controlUser ?? $this->newUser());
        $this->beUser($this->controlUser);
    }

    /**
     * Create a database user.
     */
    private function setUpDatabaseUser()
    {
        $this->databaseUser = ($this->databaseUser ?? factory(DatabaseUser::class)->create([
            'control_id' => ($this->controlUser ? $this->controlUser->id() : 1)
        ]));
        $this->be($this->databaseUser);
    }

    /**
     * Get the url of the admin side of a module.
     *
     * @param string $path Url to return relative to the admin side of the module
     * @return string Url
     */
    public function adminUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }

        return '/a/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias . $path;
    }

    /**
     * Get the url of the user side of a module.
     *
     * @param string $path Url to return relative to the user side of the module
     * @return string Url
     */
    public function userUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }

        return '/p/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias . $path;
    }

    /**
     * Get the url of the admin api.
     *
     * @param string $path Url to return relative to the admin api route
     * @return string Url
     */
    public function adminApiUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }

        return '/api/a/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias . $path;
    }

    /**
     * Get the url of the user api.
     *
     * @param string $path Url to return relative to the user api route
     * @return string Url
     */
    public function userApiUrl($path = '')
    {
        if (!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }

        return '/api/p/' . $this->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->alias . $path;
    }
}

<?php

namespace BristolSU\Support\Testing;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\PermissionTester;
use BristolSU\Support\SupportServiceProvider;
use BristolSU\Support\User\User as DatabaseUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Prophecy\Argument;

/**
 * Class TestCase
 * @package BristolSU\Support\Testing
 */
abstract class TestCase extends BaseTestCase
{

    /**
     * @var
     */
    protected $moduleInstance;

    /**
     * @var
     */
    protected $activity;

    /**
     * @var
     */
    protected $databaseUser;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $group;

    /**
     * @var
     */
    protected $role;

    /**
     * @return string
     */
    abstract public function alias(): string;

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            JavaScriptServiceProvider::class
        ];
    }

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    protected $controlClient = null;
    public function stubControl()
    {
        $control = $this->prophesize(Client::class);
        $this->instance(Client::class, $control->reveal());
    }

    public function setUp(): void {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->withFactories(__DIR__ . '/../../database/factories');

        // Create example module instance and activity
        // TODO remove support from here
        if($this->alias() !== 'support') {
            $this->activity = factory(Activity::class)->create(['slug' => 'act']);
            $this->moduleInstance = factory(ModuleInstance::class)->create(['slug' => 'mod', 'activity_id' => $this->activity->id, 'alias' => $this->alias()]);
            $this->databaseUser = factory(DatabaseUser::class)->create();
            $this->user = new User(['id' => $this->databaseUser->control_id]);
            $this->group = new Group(['id' => 3]);
            $this->role = new Role(['id' => 5]);
            $this->app->instance(Activity::class, $this->activity);
            $this->app->instance(ModuleInstance::class, $this->moduleInstance);
        }

    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
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
        $app['config']->set('auth.providers.roles', [
            'driver' => 'role-provider',
            'model' => Role::class
        ]);
        $app['config']->set('auth.providers.users', [
            'driver' => 'user-provider',
            'model' => \BristolSU\Support\Control\Models\User::class
        ]);
        $app['config']->set('auth.providers.groups', [
            'driver' => 'group-provider',
            'model' => Group::class
        ]);

        $app['config']->set('app.key', 'base64:UTyp33UhGolgzCK5CJmT+hNHcA+dJyp3+oINtX+VoPI=');

    }

    /**
     * @param $role
     * @return $this
     */
    public function beRole($role)
    {
        $this->mockControl('get', 'roles/' . $role->id, $role->toArray(), true);
        $this->app['auth']->guard('role')->loginUsingId($role->id);
        return $this;
    }

    /**
     * @param $group
     * @return $this
     */
    public function beGroup($group)
    {
        $this->mockControl('get', 'groups/' . $group->id, $group->toArray(), true);
        $this->app['auth']->guard('group')->loginUsingId($group->id);
        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function beUser($user)
    {
        $this->mockControl('get', 'students/' . $user->id, $user->toArray(), true);
        $this->app['auth']->guard('user')->loginUsingId($user->id);
        return $this;
    }

    /**
     * @param Model $expected
     * @param Model $actual
     */
    public function assertModelEquals(Model $expected, Model $actual)
    {
        $this->assertTrue($expected->is($actual), 'Models are not equal');
    }

    /**
     * @param array $true
     * @param array $false
     * @param null $user
     * @param null $group
     * @param null $role
     */
    public function createLogicTester($true=[], $false=[], $user=null, $group=null, $role=null)
    {
        $logicTester = $this->prophesize(LogicTester::class);
        foreach(Arr::wrap($true) as $logic) {
            $logicTester->evaluate(Argument::that(function($arg) use ($logic) {
                return $arg->id === $logic->id;
            }), Argument::that(function($arg) use ($user) {
                return $user === null || $user->id === $arg->id;
            }),  Argument::that(function($arg) use ($group) {
                return $group === null || $group->id === $arg->id;
            }),  Argument::that(function($arg) use ($role) {
                return $role === null || $role->id === $arg->id;
            }))->willReturn(true);
        }

        foreach(Arr::wrap($false) as $logic) {
            $logicTester->evaluate(Argument::that(function($arg) use ($logic) {
                return $arg->id === $logic->id;
            }), Argument::that(function($arg) use ($user) {
                return $user === null || $user->id === $arg->id;
            }),  Argument::that(function($arg) use ($group) {
                return $group === null || $group->id === $arg->id;
            }),  Argument::that(function($arg) use ($role) {
                return $role === null || $role->id === $arg->id;
            }))->willReturn(false);
        }

        $logicTester->evaluate(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(false);
        $this->instance(LogicTester::class, $logicTester->reveal());
    }

    /**
     * @param $method
     * @param $uri
     * @param $response
     * @param bool $inject
     */
    public function mockControl($method, $uri, $response, $inject = false) {
        if($this->controlClient === null) {
            $this->controlClient = $this->prophesize(Client::class);
        }
        $this->controlClient->request($method, $uri, Argument::any())->willReturn($response);
        if($inject) {
            $this->instance(Client::class, $this->controlClient->reveal());
        }
    }

    /**
     * @param $method
     * @param $route
     * @param null $ability
     * @param array $parameters
     */
    public function assertRequiresAuthorization($method, $route, $ability = null, $parameters=[])
    {
        $this->beUser($this->user);
        $this->beGroup($this->group);
        $this->beRole($this->role);
        $this->be($this->databaseUser, 'web');
        $this->be($this->databaseUser, 'api');
        $response = $this->call($method, $route, $parameters);
        $response->assertStatus(403, 'User allowed past authorization without permission. Is there an \'authorize\' statement?');

        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate($this->alias() . '.' . $ability)->shouldBeCalled()->willReturn(true);
        $this->instance(PermissionTester::class, $permissionTester->reveal());

        $response = $this->call($method, $route, $parameters);
        $this->assertTrue(
            $response->isSuccessful(),
            sprintf('User not allowed past authorization with permission. Status code %s', $response->getStatusCode())
        );

    }

    /**
     * @param string $path
     * @return string
     */
    public function adminUrl($path = '')
    {
        if(!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/a/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function userUrl($path = '')
    {
        if(!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/p/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function apiUrl($path = '')
    {
        if(!Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        return '/api/' . $this->moduleInstance->alias . '/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }

    public function bypassAuthorization()
    {
        $this->beUser($this->user);
        $this->beGroup($this->group);
        $this->beRole($this->role);
        $this->be($this->databaseUser, 'web');
        $this->be($this->databaseUser, 'api');
        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate(Argument::any())->willReturn(true);
        $this->instance(PermissionTester::class, $permissionTester->reveal());
    }

}

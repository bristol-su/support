<?php

namespace BristolSU\Support\Testing;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\PermissionTester;
use BristolSU\Support\SupportServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

abstract class TestCase extends BaseTestCase
{

    protected $moduleInstance;
    
    protected $activity;

    abstract public function alias(): string;
    
    public function setUp(): void {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->withFactories(__DIR__ . '/../../database/factories');
        
        // Create example module instance and activity
        $this->activity = factory(Activity::class)->create(['slug' => 'act']);
        $this->moduleInstance = factory(ModuleInstance::class)->create(['slug' => 'mod', 'activity_id' => $this->activity->id, 'alias' => $this->alias()]);

    }

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
        $app['config']->set('auth.providers.roles', [
            'driver' => 'role-provider',
            'model' => Role::class
        ]);
        $app['config']->set('auth.providers.groups', [
            'driver' => 'group-provider',
            'model' => Group::class
        ]);

        $app['config']->set('app.key', 'base64:UTyp33UhGolgzCK5CJmT+hNHcA+dJyp3+oINtX+VoPI=');
        
    }
    
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

    public function beRole($role)
    {
        $this->mockControl('get', 'position_student_groups/' . $role->id, $role->toArray(), true);
        $this->app['auth']->guard('role')->loginUsingId($role->id);
        return $this;
    }

    public function beGroup($group)
    {
        $this->mockControl('get', 'groups/' . $group->id, $group->toArray(), true);
        $this->app['auth']->guard('group')->loginUsingId($group->id);
        return $this;
    }

    public function assertModelEquals(Model $expected, Model $actual)
    {
        $this->assertTrue($expected->is($actual), 'Models are not equal');
    }

    public function createLogicTester($true=[], $false=[]   )
    {
        $logicTester = $this->prophesize(LogicTester::class);
        foreach(Arr::wrap($true) as $logic) {
            $logicTester->evaluate(Argument::that(function($arg) use ($logic) {
                return $arg->id === $logic->id;
            }))->willReturn(true);
        }

        foreach(Arr::wrap($false) as $logic) {
            $logicTester->evaluate(Argument::that(function($arg) use ($logic) {
                return $arg->id === $logic->id;
            }))->willReturn(false);
        }

        $logicTester->evaluate(Argument::any())->willReturn(false);
        $this->instance(LogicTester::class, $logicTester->reveal());
    }

    public function mockControl($method, $uri, $response, $inject = false) {
        if($this->controlClient === null) {
            $this->controlClient = $this->prophesize(Client::class);
        }
        $this->controlClient->request($method, $uri, Argument::any())->shouldBeCalled()->willReturn($response);
        if($inject) {
            $this->instance(Client::class, $this->controlClient->reveal());
        }
    }

    public function assertRequiresAuthorization($method, $route, $ability = null)
    {
        $response = $this->call($method, $route);
        $response->assertStatus(403);
        
        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate($ability)->shouldBeCalled()->willReturn(true);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());
        
        $response = $this->call($method, $route);
        dd($response);
        $this->assertContains($response->status(), [
            200,201
        ]);

    }

    public function adminUrl($path = '')
    {
        return '/a/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }

    public function userUrl($path = '')
    {
        return '/p/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }

    public function apiUrl($path = '')
    {
        return '/api/' . $this->moduleInstance->alias . '/' . $this->activity->slug . '/'. $this->moduleInstance->slug . $path;
    }
    
}

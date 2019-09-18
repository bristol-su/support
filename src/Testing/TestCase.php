<?php

namespace BristolSU\Support\Testing;

use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\SupportServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Prophecy\Argument;

class TestCase extends BaseTestCase
{

    public function setUp(): void {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->withFactories(__DIR__ . '/../../database/factories');
    }

    public function getEnvironmentSetUp($app)
    {
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

    }
    
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
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

}

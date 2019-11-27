<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ModuleInactive;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceActive;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckModuleInstanceActiveTest extends TestCase
{

    /** @test */
    public function it_throws_an_exception_if_the_module_is_not_active(){
        $this->expectException(ModuleInactive::class);

        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['active' => $logic->id]);
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->willReturn($moduleInstance);
        
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $this->createLogicTester([], [$logic], $user, $group, $role);
        $middleware = new CheckModuleInstanceActive($authentication->reveal());
        $middleware->handle($request->reveal(), function(){ });
    }
    
    /** @test */
    public function it_calls_the_callback_if_the_module_is_active(){
        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['active' => $logic->id]);
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->willReturn($moduleInstance);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $this->createLogicTester([$logic], [], $user, $group, $role);
        $middleware = new CheckModuleInstanceActive($authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });    }
    
}
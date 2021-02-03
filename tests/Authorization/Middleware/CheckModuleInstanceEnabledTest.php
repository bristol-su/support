<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authorization\Exception\ModuleInstanceDisabled;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceEnabled;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckModuleInstanceEnabledTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_the_module_instance_is_not_enabled()
    {
        $this->expectException(ModuleInstanceDisabled::class);
        
        $moduleInstance = factory(ModuleInstance::class)->create(['enabled' => false]);
        
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($moduleInstance);
        $request->route('test_callback')->shouldNotBeCalled();
        
        $middleware = new CheckModuleInstanceEnabled();
        $middleware->handle($request->reveal(), function ($request) {
            $request->route('test_callback');
        });
    }

    /** @test */
    public function it_calls_the_callback_if_the_module_instance_is_enabled()
    {
        $moduleInstance = factory(ModuleInstance::class)->create(['enabled' => true]);

        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($moduleInstance);
        $request->route('test_callback')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckModuleInstanceEnabled();
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback'));
        });
    }
}

<?php

namespace BristolSU\Support\Tests\ModuleInstance\Middleware;

use BristolSU\Support\ModuleInstance\Middleware\InjectModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Prophecy\Argument;

class InjectModuleInstanceTest extends TestCase
{
    /** @test */
    public function it_passes_the_module_in_the_request_to_the_container()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($moduleInstance);

        $app = $this->prophesize(Container::class);
        $app->instance(ModuleInstance::class, Argument::that(function ($arg) use ($moduleInstance) {
            return $arg->id === $moduleInstance->id;
        }))->shouldBeCalled();

        $middleware = new InjectModuleInstance($app->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }
}

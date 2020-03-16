<?php

namespace BristolSU\Support\Tests\Http\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Http\Middleware\InjectJavascriptVariables;
use BristolSU\Support\Testing\CreatesModuleEnvironment;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;
use Prophecy\Argument;

class InjectJavascriptVariablesTest extends TestCase
{

    use CreatesModuleEnvironment;

    public function setUp(): void
    {
        parent::setUp();
        $this->setModuleIsFor('role');
        $this->createModuleEnvironment('module-alias');
    }

    /** @test */
    public function it_sets_the_module_alias_from_the_request()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('ALIAS', $arg) && $arg['ALIAS'] === 'module-alias';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_the_activity_slug_from_the_request()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('ACTIVITY_SLUG', $arg) && $arg['ACTIVITY_SLUG'] === $this->getActivity()->slug;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_the_module_instance_slug_from_the_request()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('MODULE_INSTANCE_SLUG', $arg) && $arg['MODULE_INSTANCE_SLUG'] === $this->getModuleInstance()->slug;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }


    /** @test */
    public function it_sets_the_user_from_authentication()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('user', $arg) && $this->getControlUser()->is($arg['user']);
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_the_group_from_authentication()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);
        
        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('group', $arg) && $this->getControlGroup()->is($arg['group']);
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_the_role_from_authentication()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is(Argument::any())->willReturn(true);


        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('role', $arg) && $this->getControlRole()->is($arg['role']);
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_a_or_p_to_a_if_administrator()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is('a/*')->shouldBeCalled()->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('A_OR_P', $arg) && $arg['A_OR_P'] === 'a';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_a_or_p_to_p_if_not_administrator()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is('a/*')->shouldBeCalled()->willReturn(false);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('A_OR_P', $arg) && $arg['A_OR_P'] === 'p';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_sets_the_activity_instance()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is('a/*')->shouldBeCalled()->willReturn(false);
        
        $activityInstance = factory(ActivityInstance::class)->create();
        
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn($activityInstance);
        $this->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());
        
        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) use ($activityInstance) {
            return array_key_exists('activityinstance', $arg) && $arg['activityinstance'] instanceof ActivityInstance
                && $arg['activityinstance']->is($activityInstance);
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }


    /** @test */
    public function it_sets_the_activity_instance_to_null_if_no_activity_instance_found()
    {
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($this->getActivity());
        $request->is('a/*')->shouldBeCalled()->willReturn(false);
        
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());
        $this->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function ($arg) {
            return array_key_exists('activityinstance', $arg) && $arg['activityinstance'] === null;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = resolve(InjectJavascriptVariables::class);
        $injector->handle($request->reveal(), function ($request) {
        });
    }
}
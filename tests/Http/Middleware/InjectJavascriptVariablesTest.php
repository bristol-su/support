<?php

namespace BristolSU\Support\Tests\Http\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Http\Middleware\InjectJavascriptVariables;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;
use Prophecy\Argument;

class InjectJavascriptVariablesTest extends TestCase
{
    
    /** @test */
    public function it_sets_the_module_alias_from_the_request(){
        $authentication = $this->prophesize(Authentication::class);
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        
        $request->is(Argument::any())->willReturn(true);
        
        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('ALIAS', $arg) && $arg['ALIAS'] === 'testalias';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());
        
        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_the_activity_slug_from_the_request(){
        $authentication = $this->prophesize(Authentication::class);
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('ACTIVITY_SLUG', $arg) && $arg['ACTIVITY_SLUG'] === 'activityslug';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_the_module_instance_slug_from_the_request(){
        $authentication = $this->prophesize(Authentication::class);
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('MODULE_INSTANCE_SLUG', $arg) && $arg['MODULE_INSTANCE_SLUG'] === 'testslug';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }


    /** @test */
    public function it_sets_the_user_from_authentication(){
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn('user1');
        $authentication->getGroup()->shouldBeCalled()->willReturn('group1');
        $authentication->getRole()->shouldBeCalled()->willReturn('role1');
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is(Argument::any())->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('user', $arg) && $arg['user'] === 'user1';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_the_group_from_authentication(){
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn('user1');
        $authentication->getGroup()->shouldBeCalled()->willReturn('group1');
        $authentication->getRole()->shouldBeCalled()->willReturn('role1');
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is(Argument::any())->willReturn(true);


        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('group', $arg) && $arg['group'] === 'group1';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_the_role_from_authentication(){
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn('user1');
        $authentication->getGroup()->shouldBeCalled()->willReturn('group1');
        $authentication->getRole()->shouldBeCalled()->willReturn('role1');
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is(Argument::any())->willReturn(true);


        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('role', $arg) && $arg['role'] === 'role1';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_a_or_p_to_a_if_administrator(){
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $authentication = $this->prophesize(Authentication::class);
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is('a/*')->shouldBeCalled()->willReturn(true);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('A_OR_P', $arg) && $arg['A_OR_P'] === 'a';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_sets_a_or_p_to_p_if_not_administrator(){
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn(factory(ActivityInstance::class)->create());
        $authentication = $this->prophesize(Authentication::class);
        $request = $this->prophesize(Request::class);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn(new class {
            public $alias = 'testalias';
            public $slug = 'testslug';
        });
        $request->route('activity_slug')->shouldBeCalled()->willReturn(new class {
            public $slug = 'activityslug';
        });
        $request->is('a/*')->shouldBeCalled()->willReturn(false);

        $javascript = $this->prophesize(Transformer::class);
        $javascript->put(Argument::that(function($arg) {
            return array_key_exists('A_OR_P', $arg) && $arg['A_OR_P'] === 'p';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $javascript->reveal());

        $injector = new InjectJavascriptVariables($authentication->reveal(), $activityInstanceResolver->reveal());
        $injector->handle($request->reveal(), function($request) {});
    }
    
}
<?php

namespace BristolSU\Support\Tests\Http\View;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Http\View\InjectJavascriptVariables;
use BristolSU\Support\Testing\CreatesModuleEnvironment;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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

    private function assertViewComposerInjects(
        array $injection,
        ?Authentication $authentication = null,
        ?ActivityInstanceResolver $activityInstanceResolver = null,
        ?Request $request = null
    ) {
        if ($authentication === null) {
            $authentication = $this->prophesize(Authentication::class);
            $authentication->getUser()->willReturn(null);
            $authentication->hasUser()->willReturn(false);
            $authentication->getGroup()->willReturn(null);
            $authentication->hasGroup()->willReturn(false);
            $authentication->getRole()->willReturn(null);
            $authentication->hasRole()->willReturn(false);
            $authentication = $authentication->reveal();
        }
        if ($activityInstanceResolver === null) {
            $activityInstance = ActivityInstance::factory()->create();
            $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
            $activityInstanceResolver->getActivityInstance()->willReturn($activityInstance);
            $activityInstanceResolver = $activityInstanceResolver->reveal();
        }
        if ($request === null) {
            $route = $this->prophesize(Route::class);
            $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(true);
            $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(true);
            $request = $this->prophesize(Request::class);
            $request->route()->shouldBeCalled()->willReturn($route->reveal());
            $request->route('module_instance_slug')->willReturn($this->getModuleInstance());
            $request->route('activity_slug')->willReturn($this->getActivity());
            $request->is(Argument::any())->willReturn(false);
            $request = $request->reveal();
        }

        $viewComposer = new InjectJavascriptVariables($authentication, $activityInstanceResolver, $request);
        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function ($arg) use ($injection) {
            foreach ($injection as $key => $content) {
                if (! array_key_exists($key, $arg) || $arg[$key] !== $content) {
                    return false;
                }
            }

            return true;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());
        $viewComposer->compose($this->prophesize(View::class)->reveal());
    }

    /** @test */
    public function it_sets_admin_to_true_if_route_matches_the_test()
    {
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(false);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->is(Argument::any())->willReturn(true);

        $this->assertViewComposerInjects([
            'admin' => true
        ], null, null, $request->reveal());
    }

    /** @test */
    public function it_sets_admin_to_true_if_route_does_not_match_the_test()
    {
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(false);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->is(Argument::any())->willReturn(false);

        $this->assertViewComposerInjects([
            'admin' => false
        ], null, null, $request->reveal());
    }

    /** @test */
    public function it_injects_authentication_as_null_if_not_logged_in()
    {
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->willReturn(null);
        $authentication->hasUser()->willReturn(false);
        $authentication->getGroup()->willReturn(null);
        $authentication->hasGroup()->willReturn(false);
        $authentication->getRole()->willReturn(null);
        $authentication->hasRole()->willReturn(false);

        $this->assertViewComposerInjects([
            'user' => null,
            'group' => null,
            'role' => null
        ], $authentication->reveal());
    }

    /** @test */
    public function it_injects_authentication_if_logged_in()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);
        $authentication->hasUser()->willReturn(true);
        $authentication->hasGroup()->willReturn(true);
        $authentication->hasRole()->willReturn(true);

        $this->assertViewComposerInjects([
            'user' => $user,
            'group' => $group,
            'role' => $role
        ], $authentication->reveal());
    }

    /** @test */
    public function it_injects_the_activity_instance_from_the_resolver()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn($activityInstance);

        $this->assertViewComposerInjects([
            'activity_instance' => $activityInstance,
        ], null, $activityInstanceResolver->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_activity_instance_if_the_resolver_throws_the_exception()
    {
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
            'activity_instance' => null,
        ], null, $activityInstanceResolver->reveal());
    }

    /** @test */
    public function it_injects_the_module_instance_from_the_resolver()
    {
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(false);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->is(Argument::any())->willReturn(false);

        $this->assertViewComposerInjects([
            'module_instance' => $this->getModuleInstance(),
        ], null, null, $request->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_module_instance_if_the_resolver_throws_the_exception()
    {
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(false);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('module_instance_slug')->shouldNotBeCalled();
        $request->is(Argument::any())->willReturn(false);

        $this->assertViewComposerInjects([
            'module_instance' => null,
        ], null, null, $request->reveal());
    }

    /** @test */
    public function it_injects_the_activity_from_the_resolver()
    {
        $activity = Activity::factory()->create();
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(true);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('activity_slug')->shouldBeCalled()->willReturn($activity);
        $request->is(Argument::any())->willReturn(false);

        $this->assertViewComposerInjects([
            'activity' => $activity,
        ], null, null, $request->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_activity_if_the_resolver_throws_the_exception()
    {
        $route = $this->prophesize(Route::class);
        $route->hasParameter('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $route->hasParameter('activity_slug')->shouldBeCalled()->willReturn(false);
        $request = $this->prophesize(Request::class);
        $request->route()->shouldBeCalled()->willReturn($route->reveal());
        $request->route('activity_slug')->shouldNotBeCalled();
        $request->is(Argument::any())->willReturn(false);

        $this->assertViewComposerInjects([
            'activity' => null,
        ], null, null, $request->reveal());
    }
}

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
use Illuminate\Support\Arr;
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

    private function assertViewComposerInjects(array $injection, Authentication $authentication, ActivityInstanceResolver $activityInstanceResolver, Request $request)
    {
        $viewComposer = new InjectJavascriptVariables($authentication, $activityInstanceResolver, $request);
        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function($arg) use ($injection) {
            foreach($injection as $key => $content) {
                if(! array_key_exists($key, $arg) || $arg[$key] !== $content) {
                    return false;
                }
            }
            return true;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());
        $viewComposer->compose($this->prophesize(View::class)->reveal());
    }

    /** @test */
    public function it_sets_admin_to_true_if_route_matches_the_test(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(true);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(true);
        $authentication->getGroup()->shouldBeCalled()->willReturn(true);
        $authentication->getRole()->shouldBeCalled()->willReturn(true);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'admin' => true
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_sets_admin_to_true_if_route_does_not_match_the_test(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'admin' => false
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_authentication_as_null_if_not_logged_in(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'user' => null,
          'group' => null,
          'role' => null
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_authentication_if_logged_in(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'user' => $user,
          'group' => $group,
          'role' => $role
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_the_activity_instance_from_the_resolver(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn($activityInstance);

        $this->assertViewComposerInjects([
          'activity_instance' => $activityInstance,
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_activity_instance_if_the_resolver_throws_the_exception(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'activity_instance' => null,
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_the_module_instance_from_the_resolver(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(true);
        $request->route('module_instance_slug')->shouldBeCalled()->willReturn($this->getModuleInstance());
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'module_instance' => $this->getModuleInstance(),
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_module_instance_if_the_resolver_throws_the_exception(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $request->route('module_instance_slug')->shouldNotBeCalled();
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'module_instance' => null,
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_the_activity_from_the_resolver(){
        $activity = factory(Activity::class)->create();
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $request->has('activity_slug')->shouldBeCalled()->willReturn(true);
        $request->route('activity_slug')->shouldBeCalled()->willReturn($activity);
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'activity' => $activity,
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

    /** @test */
    public function it_injects_null_for_the_activity_if_the_resolver_throws_the_exception(){
        $request = $this->prophesize(Request::class);
        $request->has('module_instance_slug')->shouldBeCalled()->willReturn(false);
        $request->has('activity_slug')->shouldBeCalled()->willReturn(false);
        $request->route('activity_slug')->shouldNotBeCalled();
        $request->is(Argument::any())->willReturn(false);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willThrow(new NotInActivityInstanceException());

        $this->assertViewComposerInjects([
          'activity' => null,
        ], $authentication->reveal(), $activityInstanceResolver->reveal(), $request->reveal());
    }

}

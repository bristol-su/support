<?php

namespace BristolSU\Support\Tests\Filters\Listeners;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\Filters\Group\GroupNameIs;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Filters\Listeners\RefreshFilterResults;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form as FormSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class RefreshFilterResultsTest extends TestCase
{

    /** @test */
    public function it_throws_an_event_for_each_affected_filter_when_an_event_is_fired(){
        Event::fake(AudienceChanged::class);

        $user = Model::withoutEvents(fn() => User::factory()->create(['id' => 5]));
        $group = Model::withoutEvents(fn() => Group::factory()->create(['id' => 6]));
        $role = Model::withoutEvents(fn() => Role::factory()->create(['id' => 7]));

        $filterInstanceUser1 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'user_email_is']));
        $filterInstanceUser2 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'user_email_is']));
        $filterInstanceGroup1 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'group_name_is']));
        $filterInstanceGroup2 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'group_name_is']));
        $filterInstanceRole1 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'role_has_position']));
        $filterInstanceRole2 = Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'role_has_position']));

        $filterRepository = $this->prophesize(FilterRepository::class);
        $filterRepository->getAll()->willReturn([
            new RefreshFilterResultsTestDummyFilterOne(),
            new RefreshFilterResultsTestDummyFilterTwo(),
            app(RefreshFilterResultsTestDummyFilterThree::class),
            new RefreshFilterResultsTestDummyFilterFalse()
        ]);

        $listener = new RefreshFilterResults($filterRepository->reveal(), app(FilterInstanceRepository::class));
        $listener->handle(new RefreshFilterResultsTestDummyEvent());

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => count($event->filterInstances) === 2
                && (collect($event->filterInstances)->first()->is($filterInstanceUser1))
                && (collect($event->filterInstances)->last()->is($filterInstanceUser2))
                && $event->model->is($user)
        );

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => count($event->filterInstances) === 2
                && (collect($event->filterInstances)->first()->is($filterInstanceGroup1))
                && (collect($event->filterInstances)->last()->is($filterInstanceGroup2))
                && $event->model->is($group)
        );

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => count($event->filterInstances) === 2
                && (collect($event->filterInstances)->first()->is($filterInstanceRole1))
                && (collect($event->filterInstances)->last()->is($filterInstanceRole2))
                && $event->model->is($role)
        );

        Event::assertDispatchedTimes(AudienceChanged::class, 3);
    }

    /** @test */
    public function it_throws_an_exception_if_given_a_filter_class_rather_than_a_role_user_or_group_filter(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Filters must be one of user, group or role');
        Event::fake(AudienceChanged::class);

        $filterRepository = $this->prophesize(FilterRepository::class);
        $filterRepository->getAll()->willReturn([
            new RefreshFilterResultsTestDummyOtherFilter()
        ]);

        $listener = new RefreshFilterResults($filterRepository->reveal(), app(FilterInstanceRepository::class));
        $listener->handle(new RefreshFilterResultsTestDummyEvent());
    }

}

class RefreshFilterResultsTestDummyEvent
{

}

class RefreshFilterResultsTestDummyOtherFilter extends Filter
{

    public function model()
    {
        // TODO: Implement model() method.
    }

    public function options(): FormSchema
    {
        // TODO: Implement options() method.
    }

    public function hasModel(): bool
    {
        // TODO: Implement hasModel() method.
    }

    public function setModel($model)
    {
        // TODO: Implement setModel() method.
    }

    public function evaluate(array $settings): bool
    {
        // TODO: Implement evaluate() method.
    }

    public function name()
    {
        // TODO: Implement name() method.
    }

    public function description()
    {
        // TODO: Implement description() method.
    }

    public function alias()
    {
        // TODO: Implement alias() method.
    }

    public static function clearOn(): array
    {
        return [RefreshFilterResultsTestDummyEvent::class => fn() => 7];
    }
}

class RefreshFilterResultsTestDummyFilterOne extends UserEmailIs
{
    public static function clearOn(): array
    {
        return [RefreshFilterResultsTestDummyEvent::class => fn($event) => 5];
    }
}

class RefreshFilterResultsTestDummyFilterTwo extends GroupNameIs
{

    public static function clearOn(): array
    {
        return [RefreshFilterResultsTestDummyEvent::class => fn($event) => 6];
    }

}

class RefreshFilterResultsTestDummyFilterThree extends RoleHasPosition
{

    public static function clearOn(): array
    {
        return [RefreshFilterResultsTestDummyEvent::class => fn($event) => 7];
    }

}

class RefreshFilterResultsTestDummyFilterFalse extends GroupNameIs
{

    public static function clearOn(): array
    {
        return [RefreshFilterResultsTestDummyEvent::class => fn($event) => false];
    }

}

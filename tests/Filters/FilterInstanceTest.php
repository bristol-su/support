<?php


namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use FormSchema\Schema\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class FilterInstanceTest extends TestCase
{
    /** @test */
    public function it_has_a_logic()
    {
        $logic = Logic::factory()->create();
        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create([
            'logic_id' => $logic->id
        ]));

        $this->assertModelEquals($logic, $filterInstance->logic);
    }

    /** @test */
    public function name_returns_the_filter_instance_name()
    {
        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create(['name' => 'A name']));
        $this->assertEquals('A name', $filterInstance->name());
    }

    /** @test */
    public function alias_returns_the_filter_alias()
    {
        $filterInstance = \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']));;
        $this->assertEquals('alias1', $filterInstance->alias());
    }

    /** @test */
    public function settings_returns_the_filter_instance_settings()
    {
        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create(['settings' => ['setting1' => 'A Value']]));
        $this->assertEquals(['setting1' => 'A Value'], $filterInstance->settings());
    }

    /** @test */
    public function for_returns_user_if_the_filter_is_a_user_filter()
    {
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(UserFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('user', \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']))->for());
    }

    /** @test */
    public function for_returns_group_if_the_filter_is_a_group_filter()
    {
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(GroupFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('group', \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']))->for());
    }

    /** @test */
    public function for_returns_role_if_the_filter_is_a_role_filter()
    {
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(RoleFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('role', \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']))->for());
    }

    /** @test */
    public function for_throws_an_exception_if_filter_not_correct_type()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Filter must extend Filter contract');
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(new DummyFilter());
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $filterInstance = \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']));;
        $filterInstance->for();
    }

    /** @test */
    public function for_can_be_called_through_a_magic_method()
    {
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(RoleFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('role', \Illuminate\Database\Eloquent\Model::withoutEvents(fn() => FilterInstance::factory()->create(['alias' => 'alias1']))->for);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create(['name' => 'OldName']));

        $filterInstance->name = 'NewName';
        $filterInstance->save();

        $this->assertEquals(1, $filterInstance->revisionHistory->count());
        $this->assertEquals($filterInstance->id, $filterInstance->revisionHistory->first()->revisionable_id);
        $this->assertEquals(FilterInstance::class, $filterInstance->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $filterInstance->revisionHistory->first()->key);
        $this->assertEquals('OldName', $filterInstance->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $filterInstance->revisionHistory->first()->new_value);
    }

    /** @test */
    public function it_dispatches_an_audience_changed_event_if_the_logic_id_is_given_on_creation()
    {
        Event::fake(AudienceChanged::class);

        $filterInstance = FilterInstance::factory()->create(['logic_id' => Logic::factory()->create()->id]);

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => $event->filterInstances === [$filterInstance]
        );
    }

    /** @test */
    public function it_does_not_dispatch_an_audience_changed_event_if_the_logic_id_is_null_on_creation()
    {
        Event::fake(AudienceChanged::class);

        FilterInstance::factory()->create(['logic_id' => null]);

        Event::assertNotDispatched(AudienceChanged::class);
    }

    /** @test */
    public function it_dispatches_an_audience_changed_event_if_logic_id_or_logic_type_or_settings_are_changed()
    {
        Event::fake(AudienceChanged::class);

        $filterInstanceSetting = Model::withoutEvents(fn() => FilterInstance::factory()->create(['settings' => ['key' => 'value']]));
        $filterInstanceLogicId = Model::withoutEvents(fn() => FilterInstance::factory()->create(['logic_id' => Logic::factory()->create()->id]));
        $filterInstanceLogicType = Model::withoutEvents(fn() => FilterInstance::factory()->create(['logic_type' => 'all_true']));

        $filterInstanceSetting->update(['settings' => ['newkey' => 'newvalue']]);
        $filterInstanceLogicId->logic_id = Logic::factory()->create()->id;
        $filterInstanceLogicId->save();
        $filterInstanceLogicType->logic_type = 'all_false';
        $filterInstanceLogicType->save();

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => $event->filterInstances === [$filterInstanceSetting]
        );
        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => $event->filterInstances === [$filterInstanceLogicId]
        );
        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => $event->filterInstances === [$filterInstanceLogicType]
        );
    }

    /** @test */
    public function it_does_not_dispatch_an_audience_changed_event_if_the_logic_id_logic_type_and_settings_stay_the_same()
    {
        Event::fake(AudienceChanged::class);

        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create(['created_at' => Carbon::now()->subDay()]));

        $filterInstance->update(['created_at' => Carbon::now()]);

        Event::assertNotDispatched(AudienceChanged::class);
    }

    /** @test */
    public function it_dispatches_an_audience_changed_event_when_deleted()
    {
        Event::fake(AudienceChanged::class);

        $filterInstance = Model::withoutEvents(fn() => FilterInstance::factory()->create());

        $filterInstance->delete();

        Event::assertDispatched(
            AudienceChanged::class,
            fn(AudienceChanged $event) => $event->filterInstances === [$filterInstance]
        );
    }
}


class DummyFilter extends Filter
{
    /**
     * @inheritDoc
     */
    public function options(): Form
    {
        return new Form();
    }

    /**
     * @inheritDoc
     */
    public function hasModel(): bool
    {
    }

    /**
     * @inheritDoc
     */
    public function setModel($model)
    {
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
    }

    /**
     * @inheritDoc
     */
    public function description()
    {
    }

    /**
     * @inheritDoc
     */
    public function alias()
    {
    }

    /**
     * @inheritDoc
     */
    public function model()
    {
    }
}

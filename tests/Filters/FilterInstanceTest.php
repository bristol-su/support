<?php


namespace BristolSU\Support\Tests\Filters;


use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class FilterInstanceTest extends TestCase
{

    /** @test */
    public function it_has_a_logic(){
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id
        ]);

        $this->assertModelEquals($logic, $filterInstance->logic);
    }

    /** @test */
    public function name_returns_the_filter_instance_name(){
        $filterInstance = factory(FilterInstance::class)->create(['name' => 'A name']);
        $this->assertEquals('A name', $filterInstance->name());
    }

    /** @test */
    public function alias_returns_the_filter_alias(){
        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'alias1']);
        $this->assertEquals('alias1', $filterInstance->alias());
    }

    /** @test */
    public function settings_returns_the_filter_instance_settings(){
        $filterInstance = factory(FilterInstance::class)->create(['settings' => ['setting1' => 'A Value']]);
        $this->assertEquals(['setting1' => 'A Value'], $filterInstance->settings());
    }
    
    /** @test */
    public function for_returns_user_if_the_filter_is_a_user_filter(){
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(UserFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('user', factory(FilterInstance::class)->create(['alias' => 'alias1'])->for());
    }

    /** @test */
    public function for_returns_group_if_the_filter_is_a_group_filter(){
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(GroupFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('group', factory(FilterInstance::class)->create(['alias' => 'alias1'])->for());
    }

    /** @test */
    public function for_returns_role_if_the_filter_is_a_role_filter(){
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(RoleFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('role', factory(FilterInstance::class)->create(['alias' => 'alias1'])->for());
    }
    
    /** @test */
    public function for_throws_an_exception_if_filter_not_correct_type(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Filter must extend Filter contract');
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(new DummyFilter());
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'alias1']);
        $filterInstance->for();
    }
    
    /** @test */
    public function for_can_be_called_through_a_magic_method(){
        $filterRepository = $this->prophesize(FilterRepositoryContract::class);
        $filterRepository->getByAlias('alias1')->shouldBeCalled()->willReturn(
            $this->prophesize(RoleFilter::class)->reveal()
        );
        $this->app->instance(FilterRepositoryContract::class, $filterRepository->reveal());
        $this->assertEquals('role', factory(FilterInstance::class)->create(['alias' => 'alias1'])->for);
    }

}


class DummyFilter extends Filter {

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        // TODO: Implement options() method.
    }

    /**
     * @inheritDoc
     */
    public function hasModel(): bool
    {
        // TODO: Implement hasModel() method.
    }

    /**
     * @inheritDoc
     */
    public function setModel($model)
    {
        // TODO: Implement setModel() method.
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
        // TODO: Implement evaluate() method.
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        // TODO: Implement name() method.
    }

    /**
     * @inheritDoc
     */
    public function description()
    {
        // TODO: Implement description() method.
    }

    /**
     * @inheritDoc
     */
    public function alias()
    {
        // TODO: Implement alias() method.
    }
}
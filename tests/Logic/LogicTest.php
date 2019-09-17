<?php


namespace BristolSU\Support\Tests\Logic;


use BristolSU\Support\Filters\Contracts\FilterManager;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Collection;
use BristolSU\Support\Tests\TestCase;

class LogicTest extends TestCase
{

    /** @test */
    public function all_true_filters_returns_all_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]);

        $this->assertCount(1, $logic->allTrueFilters);
        $this->assertModelEquals($filterInstance, $logic->allTrueFilters->first());
    }

    /** @test */
    public function all_true_filters_is_an_empty_array_if_logic_has_no_all_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $this->assertIsArray($logic->allTrueFilters->toArray());
        $this->assertEmpty($logic->allTrueFilters->toArray());
    }

    /** @test */
    public function all_true_only_returns_all_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]);
        $filterInstance2 = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_false'
        ]);

        $this->assertCount(1, $logic->allTrueFilters);
        $this->assertModelEquals($filterInstance, $logic->allTrueFilters->first());
    }

    /** @test */
    public function it_has_all_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_false'
        ]);

        $this->assertCount(1, $logic->allFalseFilters);
        $this->assertModelEquals($filterInstance, $logic->allFalseFilters->first());
    }


    /** @test */
    public function all_false_filters_returns_an_empty_array_if_no_all_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $this->assertIsArray($logic->allFalseFilters->toArray());
        $this->assertEmpty($logic->allFalseFilters->toArray());
    }

    /** @test */
    public function all_false_filters_returns_only_all_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_false'
        ]);
        $filterInstance2 = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]);

        $this->assertCount(1, $logic->allFalseFilters);
        $this->assertModelEquals($filterInstance, $logic->allFalseFilters->first());
    }

    /** @test */
    public function it_has_any_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_true'
        ]);

        $this->assertCount(1, $logic->anyTrueFilters);
        $this->assertModelEquals($filterInstance, $logic->anyTrueFilters->first());
    }

    /** @test */
    public function any_true_filters_returns_an_empty_array_if_no_any_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $this->assertIsArray($logic->anyTrueFilters->toArray());
        $this->assertEmpty($logic->anyTrueFilters->toArray());
    }

    /** @test */
    public function any_true_filters_returns_only_any_true_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_true'
        ]);
        $filterInstance2 = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]);

        $this->assertCount(1, $logic->anyTrueFilters);
        $this->assertModelEquals($filterInstance, $logic->anyTrueFilters->first());
    }

    /** @test */
    public function it_has_any_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_false'
        ]);

        $this->assertCount(1, $logic->anyFalseFilters);
        $this->assertModelEquals($filterInstance, $logic->anyFalseFilters->first());
    }

    /** @test */
    public function any_false_filters_returns_an_empty_array_if_no_any_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $this->assertIsArray($logic->anyFalseFilters->toArray());
        $this->assertEmpty($logic->anyFalseFilters->toArray());
    }

    /** @test */
    public function any_false_filters_returns_only_any_false_filters()
    {
        $logic = factory(Logic::class)->create();
        $filterInstance = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_false'
        ]);
        $filterInstance2 = factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]);

        $this->assertCount(1, $logic->anyFalseFilters);
        $this->assertModelEquals($filterInstance, $logic->anyFalseFilters->first());
    }

    /** @test */
    public function filters_can_retrieve_all_filters()
    {
        $logic = factory(Logic::class)->create();
        $filters = new Collection;
        $filters->push(factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_false'
        ]));
        $filters->push(factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_true'
        ]));
        $filters->push(factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'all_false'
        ]));
        $filters->push(factory(FilterInstance::class)->create([
            'logic_id' => $logic->id,
            'logic_type' => 'any_true'
        ]));

        $allFilters = $logic->filters;
        foreach ($filters as $filter) {
            $this->assertModelEquals($filter, $allFilters->shift());
        }
    }

    /** @test */
    public function getLowestResourceAttribute_returns_role_if_there_is_a_role_filter()
    {
        app(FilterManager::class)->register('dummyrole_1', DummyRoleFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyrole_1']);
        
        $this->assertEquals('role', $logic->lowestResource);
    }

    /** @test */
    public function getLowestResourceAttribute_returns_group_if_there_is_a_group_filter_and_no_role_filter()
    {
        app(FilterManager::class)->register('dummygroup_1', DummyGroupFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummygroup_1']);

        $this->assertEquals('group', $logic->lowestResource);
    }

    /** @test */
    public function getLowestResourceAttribute_returns_role_if_there_is_a_role_filter_and_a_group_filter()
    {
        app(FilterManager::class)->register('dummyrole_1', DummyRoleFilter::class);
        app(FilterManager::class)->register('dummygroup_1', DummyGroupFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummygroup_1']);
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyrole_1']);

        $this->assertEquals('role', $logic->lowestResource);
    }

    /** @test */
    public function getLowestResourceAttribute_returns_user_if_there_is_only_a_user_filter()
    {
        app(FilterManager::class)->register('dummyuser_1', DummyUserFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyuser_1']);

        $this->assertEquals('user', $logic->lowestResource);  
    }

    /** @test */
    public function getLowestResourceAttribute_returns_group_if_there_is_a_user_filter_and_a_group_filter()
    {
        app(FilterManager::class)->register('dummyuser_1', DummyUserFilter::class);
        app(FilterManager::class)->register('dummygroup_1', DummyGroupFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummygroup_1']);
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyuser_1']);

        $this->assertEquals('group', $logic->lowestResource);
    }

    /** @test */
    public function getLowestResourceAttribute_returns_role_if_there_is_a_user_filter_and_a_role_filter()
    {
        app(FilterManager::class)->register('dummyrole_1', DummyRoleFilter::class);
        app(FilterManager::class)->register('dummyuser_1', DummyUserFilter::class);
        $logic = factory(Logic::class)->create();
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyuser_1']);
        $filter = factory(FilterInstance::class)->create(['logic_id' => $logic->id, 'alias' => 'dummyrole_1']);

        $this->assertEquals('role', $logic->lowestResource);
    }

    /** @test */
    public function getLowestResourceAttribute_returns_none_if_there_is_are_no_filters()
    {
        $logic = factory(Logic::class)->create();

        $this->assertEquals('none', $logic->lowestResource);
    }


}

class DummyUserFilter extends UserFilter
{

    private $result = true;

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        return [];
    }

    public function setResultTo($value)
    {
        $this->result = $value;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
        return $this->result;
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
        return 'dummyuser_1';
    }
}

class DummyGroupFilter extends GroupFilter
{

    private $result = true;

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        return [];
    }

    public function setResultTo($value)
    {
        $this->result = $value;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
        return $this->result;
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
        return 'dummygroup_1';
    }
}

class DummyRoleFilter extends RoleFilter
{

    private $result = true;

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        return [];
    }

    public function setResultTo($value)
    {
        $this->result = $value;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
        return $this->result;
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
        return 'dummyrole_1';
    }
}
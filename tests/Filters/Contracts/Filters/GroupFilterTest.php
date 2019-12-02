<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Tests\TestCase;

class GroupFilterTest extends TestCase
{

    /** @test */
    public function getModel_returns_the_set_model(){
        $filter = new DummyGroupFilter();
        $group = new Group(['id' => 1]);
        $filter->setModel($group);
        $this->assertEquals($group, $filter->model());
    }

    /** @test */
    public function hasModel_returns_true_if_the_group_is_set(){
        $filter = new DummyGroupFilter();
        $dummyGroup = new Group(['id' => 1]);
        $filter->setModel($dummyGroup);

        $this->assertTrue($filter->hasModel());
    }
    
    /** @test */
    public function hasModel_returns_false_if_the_group_is_not_set(){
        $filter = new DummyGroupFilter();

        $this->assertFalse($filter->hasModel());
    }
    
    /** @test */
    public function setModel_throws_exception_if_model_not_of_type_group(){
        $fakeGroup = new class {};
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot pass a class of type [' . get_class($fakeGroup) . '] to a group filter');
        $filter = new DummyGroupFilter();
        $filter->setModel($fakeGroup);
    }
    
    /** @test */
    public function group_returns_the_group(){
        $filter = new DummyGroupFilter();
        $group = new Group(['id' => 1]);
        $filter->setModel($group);
        $this->assertEquals($group, $filter->group());
    }
    
}

class DummyGroupFilter extends GroupFilter
{

    /**
     * Get possible options as an array
     *
     * @return array
     */
    public function options(): array
    {
        // TODO: Implement options() method.
    }

    /**
     * Test if the filter passes
     *
     * @param Object $model Group, Role or User
     * @param string $settings Key of the chosen option
     *
     * @return bool
     */
    public function evaluate($settings): bool
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

    public function audience($settings)
    {
        // TODO: Implement audience() method.
    }
}
<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class GroupFilterTest extends TestCase
{

    /** @test */
    public function getModel_returns_the_set_model(){
        $filter = new DummyGroupFilter();
        $group = $this->newGroup();
        $filter->setModel($group);
        $this->assertEquals($group, $filter->model());
    }

    /** @test */
    public function hasModel_returns_true_if_the_group_is_set(){
        $filter = new DummyGroupFilter();
        $dummyGroup = $this->newGroup();
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
        $group = $this->newGroup();
        $filter->setModel($group);
        $this->assertEquals($group, $filter->group());
    }

}

class DummyGroupFilter extends GroupFilter
{

    public function options(): Form
    {
        return new Form();
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
    }

    public function name()
    {
    }

    public function description()
    {
    }

    public function alias()
    {
    }

    public function audience($settings)
    {
    }
}

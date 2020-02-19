<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class RoleFilterTest extends TestCase
{

    /** @test */
    public function getModel_returns_the_set_model(){
        $filter = new DummyRoleFilter();
        $role = $this->newRole(['id' => 1]);
        $filter->setModel($role);
        $this->assertEquals($role, $filter->model());
    }

    /** @test */
    public function hasModel_returns_true_if_the_role_is_set(){
        $filter = new DummyRoleFilter();
        $dummyRole = $this->newRole(['id' => 1]);
        $filter->setModel($dummyRole);

        $this->assertTrue($filter->hasModel());
    }

    /** @test */
    public function hasModel_returns_false_if_the_role_is_not_set(){
        $filter = new DummyRoleFilter();

        $this->assertFalse($filter->hasModel());
    }

    /** @test */
    public function setModel_throws_exception_if_model_not_of_type_role(){
        $fakeRole = new class {};
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot pass a class of type [' . get_class($fakeRole) . '] to a role filter');
        $filter = new DummyRoleFilter();
        $filter->setModel($fakeRole);
    }

    /** @test */
    public function role_returns_the_role(){
        $filter = new DummyRoleFilter();
        $role = $this->newRole(['id' => 1]);
        $filter->setModel($role);
        $this->assertEquals($role, $filter->role());
    }


}

class DummyRoleFilter extends RoleFilter
{

    public function options(): Form
    {
        return new Form();
    }
    /**
     * Test if the filter passes
     *
     * @param Object $model Role, Role or User
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
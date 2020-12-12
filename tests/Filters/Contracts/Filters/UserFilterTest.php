<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class UserFilterTest extends TestCase
{

    /** @test */
    public function getModel_returns_the_set_model(){
        $filter = new DummyUserFilter();
        $user = $this->newUser();
        $filter->setModel($user);
        $this->assertEquals($user, $filter->model());
    }

    /** @test */
    public function hasModel_returns_true_if_the_user_is_set(){
        $filter = new DummyUserFilter();
        $dummyUser = $this->newUser();
        $filter->setModel($dummyUser);

        $this->assertTrue($filter->hasModel());
    }

    /** @test */
    public function hasModel_returns_false_if_the_user_is_not_set(){
        $filter = new DummyUserFilter();

        $this->assertFalse($filter->hasModel());
    }

    /** @test */
    public function setModel_throws_exception_if_model_not_of_type_user(){
        $fakeUser = new class {};
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot pass a class of type [' . get_class($fakeUser) . '] to a user filter');
        $filter = new DummyUserFilter();
        $filter->setModel($fakeUser);
    }

    /** @test */
    public function user_returns_the_user(){
        $filter = new DummyUserFilter();
        $user = $this->newUser();
        $filter->setModel($user);
        $this->assertEquals($user, $filter->user());
    }


}

class DummyUserFilter extends UserFilter
{

    public function options(): Form
    {
        return new Form();
    }

    /**
     * Test if the filter passes
     *
     * @param Object $model User, Role or User
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

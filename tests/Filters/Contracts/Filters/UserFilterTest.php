<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\User\User;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Tests\TestCase;

class UserFilterTest extends TestCase
{

    /** @test */
    public function getModel_returns_the_set_model(){
        $filter = new DummyUserFilter();
        $user = factory(User::class)->create();
        $filter->setModel($user);
        $this->assertEquals($user->id, $filter->model()->id);
    }

    /** @test */
    public function for_returns_user()
    {
        $filter = new DummyUserFilter();

        $this->assertEquals('user', $filter->for());
    }

    /** @test */
    public function hasModel_returns_true_if_the_user_is_set(){
        $filter = new DummyUserFilter();
        $user = factory(User::class)->create();
        $filter->setModel($user);

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
    
}

class DummyUserFilter extends UserFilter
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
     * @param Object $model User, Role or User
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
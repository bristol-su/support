<?php


namespace BristolSU\Support\Tests\Filters;


use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterTester;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Prophecy\Argument;

class FilterTesterTest extends TestCase
{

    /** @test */
    public function it_evaluates_a_filter(){
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference1'])->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias');
        $filterInstance->settings()->shouldBeCalled()->willReturn(['tag' => 'reference1']);

        $filterTester = new FilterTester($repository->reveal());
        $this->assertTrue($filterTester->evaluate($filterInstance->reveal()));
    }

    /** @test */
    public function it_returns_false_if_the_filter_does_not_have_a_model(){
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('alias')->shouldBeCalled()->willReturn($filter->reveal());

        $filterInstance = $this->prophesize(FilterInstance::class);
        $filterInstance->alias()->shouldBeCalled()->willReturn('alias');

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse($filterTester->evaluate($filterInstance->reveal()));

    }
    
    /** @test */
    public function it_returns_true_if_the_filter_is_true(){

        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);
        
        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference'])->shouldBeCalled()->willReturn(true);
        
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());
        
        $filterTester = new FilterTester($repository->reveal());
        $this->assertTrue(
            $filterTester->evaluate($filterInstance)
        );
    }

    /** @test */
    public function it_returns_false_if_the_filter_is_false(){
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);

        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(true);
        $filter->evaluate(['tag' => 'reference'])->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse(
            $filterTester->evaluate($filterInstance)
        );
    }

    /** @test */
    public function it_returns_false_if_the_model_is_not_given(){
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'group_tagged',
            'settings' => ['tag' => 'reference']
        ]);

        $filter = $this->prophesize(Filter::class);
        $filter->hasModel()->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('group_tagged')->shouldBeCalled()->willReturn($filter->reveal());

        $filterTester = new FilterTester($repository->reveal());
        $this->assertFalse(
            $filterTester->evaluate($filterInstance)
        );
    }
    
    /** @test */
    public function it_sets_the_user_if_the_filter_is_a_user_filter(){
        $filter = $this->prophesize(UserFilter::class);
        $user = factory(User::class)->create();
        $filter->setModel(Argument::that(function($arg) use ($user) {
            return $user->id === $arg->id;
        }))->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());
        
        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);
        
        $tester = new FilterTester($repository->reveal());
        $tester->setUser($user);
        $tester->evaluate($filterInstance);
        
    }

    /** @test */
    public function it_does_not_set_the_user_if_the_filter_is_a_user_filter_but_no_user_given(){
        $filter = $this->prophesize(UserFilter::class);
        $user = factory(User::class)->create();
        $filter->setModel(Argument::that(function($arg) use ($user) {
            return $user->id === $arg->id;
        }))->shouldNotBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance);

    }

    /** @test */
    public function it_sets_the_group_if_the_filter_is_a_group_filter(){
        $filter = $this->prophesize(GroupFilter::class);
        $group = new Group(['id' => 1]);
        $filter->setModel($group)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->setGroup($group);
        $tester->evaluate($filterInstance);

    }

    /** @test */
    public function it_does_not_set_the_group_if_the_filter_is_a_group_filter_but_no_group_given(){
        $filter = $this->prophesize(GroupFilter::class);
        $group = new Group(['id' => 1]);
        $filter->setModel($group)->shouldNotBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance);

    }

    /** @test */
    public function it_sets_the_role_if_the_filter_is_a_role_filter(){
        $filter = $this->prophesize(RoleFilter::class);
        $role = new Role(['id' => 1]);
        $filter->setModel($role)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->setRole($role);
        $tester->evaluate($filterInstance);

    }

    /** @test */
    public function it_does_not_set_the_role_if_the_filter_is_a_role_filter_but_no_role_given(){
        $filter = $this->prophesize(RoleFilter::class);
        $role = new Role(['id' => 1]);
        $filter->setModel($role)->shouldNotBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance);

    }

    /** @test */
    public function it_sets_the_user_if_passed_to_evaluate(){
        $filter = $this->prophesize(UserFilter::class);
        $user = factory(User::class)->create();
        $filter->setModel(Argument::that(function($arg) use ($user) {
            return $user->id === $arg->id;
        }))->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance, $user);
    }
    
    /** @test */
    public function it_replaces_a_user_set_via_setUser(){
        $filter = $this->prophesize(UserFilter::class);
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $filter->setModel(Argument::that(function($arg) use ($user2) {
            return $user2->id === $arg->id;
        }))->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->setUser($user1);
        $tester->evaluate($filterInstance, $user2);
    }

    /** @test */
    public function it_sets_the_group_if_passed_to_evaluate(){
        $filter = $this->prophesize(GroupFilter::class);
        $group = new Group(['id' => 1]);
        $filter->setModel($group)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance, null, $group);
    }

    /** @test */
    public function it_replaces_a_group_set_via_setGroup(){
        $filter = $this->prophesize(GroupFilter::class);
        $group1 = new Group(['id' => 1]);
        $group2 = new Group(['id' => 2]);
        $filter->setModel($group2)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->setGroup($group1);
        $tester->evaluate($filterInstance, null, $group2);
    }

    /** @test */
    public function it_sets_the_role_if_passed_to_evaluate(){
        $filter = $this->prophesize(RoleFilter::class);
        $role = new Role(['id' => 1]);
        $filter->setModel($role)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->evaluate($filterInstance, null, null, $role);
    }
 
    /** @test */
    public function it_replaces_a_role_set_via_setRole(){
        $filter = $this->prophesize(RoleFilter::class);
        $role1 = new Role(['id' => 1]);
        $role2 = new Role(['id' => 2]);
        $filter->setModel($role2)->shouldBeCalled();
        $filter->hasModel()->willReturn(true);
        $filter->evaluate(Argument::any())->willReturn(true);
        $repository = $this->prophesize(FilterRepository::class);
        $repository->getByAlias('filteralias')->willReturn($filter->reveal());

        $filterInstance = factory(FilterInstance::class)->create(['alias' => 'filteralias']);

        $tester = new FilterTester($repository->reveal());
        $tester->setRole($role1);
        $tester->evaluate($filterInstance, null, null, $role2);
    }
    
}

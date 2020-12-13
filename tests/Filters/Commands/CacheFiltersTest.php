<?php

namespace BristolSU\Support\Tests\Filters\Commands;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Jobs\CacheFilter;
use BristolSU\Support\Filters\Jobs\CacheFilter as CacheFilterJob;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class CacheFiltersTest extends TestCase
{

    /** @test */
    public function it_caches_a_user_filter_instances_with_users()
    {
        Queue::fake();

        $userFilterInstances = [];
        for($i = 0; $i < 10; $i++) {
            $fi = $this->prophesize(FilterInstanceContract::class);
            $fi->for()->shouldBeCalled()->willReturn('user');
            $userFilterInstances[] = $fi->reveal();
        }

        $users = [
            $this->newUser(),
            $this->newUser(),
            $this->newUser(),
        ];

        $filterInstanceRepository = $this->prophesize(FilterInstanceRepository::class);
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $filterInstanceRepository->all()->shouldBeCalled()->willReturn(collect($userFilterInstances));
        $userRepo->all()->shouldBeCalled()->willReturn(collect($users));
        $groupRepo->all()->shouldBeCalled()->willReturn(collect());
        $roleRepo->all()->shouldBeCalled()->willReturn(collect());

        $this->app->instance(FilterInstanceRepository::class, $filterInstanceRepository->reveal());
        $this->app->instance(UserRepository::class, $userRepo->reveal());
        $this->app->instance(GroupRepository::class, $groupRepo->reveal());
        $this->app->instance(RoleRepository::class, $roleRepo->reveal());

        $this->artisan('filters:cache');

        foreach ($userFilterInstances as $filterInstance) {
            foreach ($users as $model) {
                Queue::assertPushed(CacheFilterJob::class, function (CacheFilter $job) use ($filterInstance, $model) {
                    return $job->filterInstance() === $filterInstance && $job->model() === $model;
                });
            }
        }
    }

    /** @test */
    public function it_caches_a_group_filter_instances_with_groups()
    {
        Queue::fake();

        $groupFilterInstances = [];
        for($i = 0; $i < 10; $i++) {
            $gfi = $this->prophesize(FilterInstanceContract::class);
            $gfi->for()->shouldBeCalled()->willReturn('group');
            $groupFilterInstances[] = $gfi->reveal();
        }

        $groups = [
            $this->newGroup(),
            $this->newGroup(),
            $this->newGroup(),
        ];

        $filterInstanceRepository = $this->prophesize(FilterInstanceRepository::class);
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $filterInstanceRepository->all()->shouldBeCalled()->willReturn(collect($groupFilterInstances));
        $userRepo->all()->shouldBeCalled()->willReturn(collect());
        $groupRepo->all()->shouldBeCalled()->willReturn(collect($groups));
        $roleRepo->all()->shouldBeCalled()->willReturn(collect());

        $this->app->instance(FilterInstanceRepository::class, $filterInstanceRepository->reveal());
        $this->app->instance(UserRepository::class, $userRepo->reveal());
        $this->app->instance(GroupRepository::class, $groupRepo->reveal());
        $this->app->instance(RoleRepository::class, $roleRepo->reveal());

        $this->artisan('filters:cache');

        foreach ($groupFilterInstances as $filterInstance) {
            foreach ($groups as $model) {
                Queue::assertPushed(CacheFilterJob::class, function (CacheFilter $job) use ($filterInstance, $model) {
                    return $job->filterInstance() === $filterInstance && $job->model() === $model;
                });
            }
        }
    }

    /** @test */
    public function it_caches_a_role_filter_instances_with_roles()
    {
        Queue::fake();

        $roleFilterInstances = [];
        for($i = 0; $i < 10; $i++) {
            $gfi = $this->prophesize(FilterInstanceContract::class);
            $gfi->for()->shouldBeCalled()->willReturn('role');
            $roleFilterInstances[] = $gfi->reveal();
        }

        $roles = [
            $this->newRole(),
            $this->newRole(),
            $this->newRole(),
        ];

        $filterInstanceRepository = $this->prophesize(FilterInstanceRepository::class);
        $userRepo = $this->prophesize(UserRepository::class);
        $groupRepo = $this->prophesize(GroupRepository::class);
        $roleRepo = $this->prophesize(RoleRepository::class);

        $filterInstanceRepository->all()->shouldBeCalled()->willReturn(collect($roleFilterInstances));
        $userRepo->all()->shouldBeCalled()->willReturn(collect());
        $groupRepo->all()->shouldBeCalled()->willReturn(collect());
        $roleRepo->all()->shouldBeCalled()->willReturn(collect($roles));

        $this->app->instance(FilterInstanceRepository::class, $filterInstanceRepository->reveal());
        $this->app->instance(UserRepository::class, $userRepo->reveal());
        $this->app->instance(GroupRepository::class, $groupRepo->reveal());
        $this->app->instance(RoleRepository::class, $roleRepo->reveal());

        $this->artisan('filters:cache');

        foreach ($roleFilterInstances as $filterInstance) {
            foreach ($roles as $model) {
                Queue::assertPushed(CacheFilterJob::class, function (CacheFilter $job) use ($filterInstance, $model) {
                    return $job->filterInstance() === $filterInstance && $job->model() === $model;
                });
            }
        }
    }

}

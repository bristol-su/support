<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class CacheLogicTest extends TestCase
{

    /** @test */
    public function it_dispatches_all_users_in_chunks()
    {
        Bus::fake();

        $logic = Logic::factory()->create();
        $users = Model::withoutEvents(fn() => User::factory()->count(100)->create());

        $userRepository = $this->prophesize(\BristolSU\ControlDB\Contracts\Repositories\User::class);
        $userRepository->all()->willReturn($users);

        $job = new CacheLogic($logic);
        $job->handle($userRepository->reveal());
        Bus::assertDispatchedTimes(CacheLogicForUser::class, 10);
    }

}

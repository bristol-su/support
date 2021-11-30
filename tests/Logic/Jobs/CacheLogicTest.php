<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Jobs\CacheLogicsForUser;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class CacheLogicTest extends TestCase
{

    /** @test */
    public function it_dispatches_all_user_pages()
    {
        Bus::fake();

        $logic = Logic::factory()->create();

        $userRepository = $this->prophesize(\BristolSU\ControlDB\Contracts\Repositories\User::class);
        $userRepository->count()->willReturn(20007);
        $this->app->instance(\BristolSU\ControlDB\Contracts\Repositories\User::class, $userRepository->reveal());

        $job = new CacheLogic($logic);
        $job->handle();
        Bus::assertDispatchedTimes(CacheLogicsForUser::class, 101);
        for($i = 1; $i <= 101;$i++) {
            Bus::assertDispatched(CacheLogicsForUser::class, fn($job) => $job->page === $i);
        }
    }

}

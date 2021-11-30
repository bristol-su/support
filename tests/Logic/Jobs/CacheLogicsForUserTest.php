<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Jobs\CacheLogicsForUser;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class CacheLogicsForUserTest extends TestCase
{

    /** @test */
    public function it_dispatches_all_users_in_page_in_chunks()
    {
        Bus::fake();

        $logic = Logic::factory()->create();

        $users = Model::withoutEvents(fn() => User::factory()->count(200)->create());

        $userRepository = $this->prophesize(\BristolSU\ControlDB\Contracts\Repositories\User::class);
        $userRepository->paginate(2, 200)->willReturn($users);

        $job = new CacheLogicsForUser($logic, 2);
        $job->handle($userRepository->reveal());
        Bus::assertDispatchedTimes(CacheLogicForUser::class, 10);
    }

}

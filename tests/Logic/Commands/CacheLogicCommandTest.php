<?php

namespace BristolSU\Support\Tests\Logic\Commands;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class CacheLogicCommandTest extends TestCase
{

    /** @test */
    public function it_dispatches_all_users_in_chunks()
    {
        Bus::fake();

        $users = Model::withoutEvents(fn() => User::factory()->count(100)->create());
        $logics = Logic::factory()->count(30)->create();

        $this->artisan('logic:cache')
            ->assertExitCode(0)
            ->run();

        Bus::assertDispatchedTimes(CacheLogic::class, 30);
        foreach($logics as $logic) {
            Bus::assertDispatched(CacheLogic::class, fn(CacheLogic $job) => $job->logic->is($logic));
        }
    }

}

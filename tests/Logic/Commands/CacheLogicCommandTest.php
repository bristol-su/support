<?php

namespace BristolSU\Support\Tests\Logic\Commands;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
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

        $this->artisan('logic:cache')
            ->assertExitCode(0)
            ->run();

        Bus::assertDispatchedTimes(CacheLogicForUser::class, 10);
    }

}

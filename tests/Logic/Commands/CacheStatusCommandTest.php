<?php

namespace BristolSU\Support\Tests\Logic\Commands;

use _PHPStan_76800bfb5\Symfony\Component\Console\Input\Input;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Commands\CacheStatusCommand;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;

class CacheStatusCommandTest extends TestCase
{

    /** @test */
    public function it_shows_all_logics_and_the_cached_number()
    {
        $logic1 = Logic::factory()->create();
        $logic2 = Logic::factory()->create();

        $users = Model::withoutEvents(fn() => User::factory()->count(100)->create());

        foreach ($users->take(50) as $user) {
            LogicResult::insert([
                'logic_id' => $logic1->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }
        foreach ($users->take(99) as $user) {
            LogicResult::insert([
                'logic_id' => $logic2->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }

        $this->artisan('logic:cache-status')
            ->expectsTable(['ID', 'Name', 'Cached'], [
                [$logic1->id, $logic1->name, 50],
                [$logic2->id, $logic2->name, 99],
            ])
            ->assertExitCode(0)
            ->run();
    }

    /** @test */
    public function if_a_logic_id_is_given_just_that_logic_is_shown()
    {
        $logic1 = Logic::factory()->create();
        $logic2 = Logic::factory()->create();

        $users = Model::withoutEvents(fn() => User::factory()->count(100)->create());

        foreach ($users->take(50) as $user) {
            LogicResult::insert([
                'logic_id' => $logic1->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }
        foreach ($users->take(99) as $user) {
            LogicResult::insert([
                'logic_id' => $logic2->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }

        $this->artisan('logic:cache-status ' . $logic2->id)
            ->expectsTable(['ID', 'Name', 'Cached'], [
                [$logic2->id, $logic2->name, 99]
            ])
            ->assertExitCode(0)
            ->run();
    }


    /** @test */
    public function it_shows_all_logics_and_the_cached_number_compared_with_the_total()
    {
        $users = Model::withoutEvents(fn() => User::factory()->count(98)->create());
        $user1 = Model::withoutEvents(fn() => User::factory()->create());
        $user2 = Model::withoutEvents(fn() => User::factory()->create());

        $logic1 = Logic::factory()->create(['user_id' => $user1->id()]);
        $logic2 = Logic::factory()->create(['user_id' => $user1->id()]);

        $group = Model::withoutEvents(fn() => $this->newGroup());
        $group->addUser($user1);

        $role = Model::withoutEvents(fn() => $this->newRole());
        Model::withoutEvents(fn() => $role->addUser($user2));

        LogicResult::all()->each(fn(LogicResult $result) => $result->delete());

        foreach ($users->take(50) as $user) {
            LogicResult::insert([
                'logic_id' => $logic1->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }
        foreach ($users->take(95) as $user) {
            LogicResult::insert([
                'logic_id' => $logic2->id,
                'user_id' => $user->id(),
                'result' => true
            ]);
        }

        $this->artisan('logic:cache-status --percentage')
            ->expectsTable(['ID', 'Name', 'Cached'], [
                [$logic1->id, $logic1->name, '50/102 (49%)'],
                [$logic2->id, $logic2->name, '95/102 (93%)'],
            ])
            ->assertExitCode(0);
    }

}

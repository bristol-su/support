<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

/**
 * Job to cache a filter result.
 */
class CacheLogic implements ShouldQueue
{
    use Queueable, Dispatchable;

    public Logic $logic;

    public function __construct(Logic $logic)
    {
        $this->logic = $logic;
        $this->onQueue('logic');
    }

    public function handle()
    {
        foreach($this->pages() as $page) {
            CacheLogicsForUser::dispatch($this->logic, $page);
        }
    }

    private function pages(): array
    {
        $count = app(User::class)->count();

        if($count > 0) {
            return range(1, ceil($count/200));
        }
        return [];
    }
}

<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        $this->onQueue(sprintf('logic_%s', config('app.env')));
    }

    public function handle()
    {
        $pages = $this->pages();
        foreach ($pages as $page) {
            \Log::info(sprintf('There are %u pages of users', $pages));
            CacheLogicsForUser::dispatch($this->logic, $page);
        }
    }

    private function pages(): array
    {
        $count = app(User::class)->count();

        if ($count > 0) {
            return range(1, ceil($count/200));
        }

        return [];
    }
}

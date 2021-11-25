<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Logic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Job to cache a filter result.
 */
class CacheLogic implements ShouldQueue
{
    use Queueable;

    private Logic $logic;

    public function __construct(Logic $logic)
    {
        $this->logic = $logic;
    }

    public function handle(UserRepository $userRepository)
    {
        $page = 1;

        do {
            $users = $userRepository->paginate($page, 50);
            if(count($users) > 0) {
                dispatch(new CacheLogicForUser($users->all(), $this->argument('logic')));
            }
            $page = $page + 1;
        } while (count($users) > 0);
    }
}

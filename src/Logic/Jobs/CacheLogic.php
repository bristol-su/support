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
        $users = collect($userRepository->all());

        foreach($users->chunk(50) as $userChunk) {
            dispatch(new CacheLogicForUser($userChunk->all(), $this->logic->id));
        }
    }
}

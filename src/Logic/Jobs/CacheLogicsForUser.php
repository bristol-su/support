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
class CacheLogicsForUser implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels;

    public Logic $logic;
    
    public int $page;

    public function __construct(Logic $logic, $page = 1)
    {
        $this->logic = $logic;
        $this->page = $page;
    }

    public function handle(UserRepository $userRepository)
    {
        $allUsers = $userRepository->paginate($this->page, 200);
        foreach($allUsers->chunk(20) as $users) {
            CacheLogicForUser::dispatch($users->all(), $this->logic->id);
        }
    }
}

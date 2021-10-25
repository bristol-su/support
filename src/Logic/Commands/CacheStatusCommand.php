<?php

namespace BristolSU\Support\Logic\Commands;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Jobs\CacheFilter;
use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Command to cache the result of all filters.
 */
class CacheStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logic:cache-status {logic? : The ID of the logic to see the status of} {--percentage : Show the percentage cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'See the status of one or all logic caches';

    /**
     * Cache the result of all filter instances.
     *
     * This function will fire a job to cache the filter of all filter instances.
     *
     * @param LogicRepository $logicRepository
     * @param UserRepository $userRepository Repository to get all users from
     */
    public function handle(
        LogicRepository $logicRepository,
        UserRepository  $userRepository
    )
    {
        $users = collect($userRepository->all());
        $combinations = 0;

        if ($this->option('percentage')) {
            $this->info('Counting possible combinations');
            foreach ($users as $user) {
                $audience = Audience::fromUser($user);
                $combinations = $combinations + $audience->roles()->count();
                $combinations = $combinations + $audience->groups()->count();
                if ($audience->canBeUser()) {
                    $combinations = $combinations + 1;
                }
            }
        }

        $results = [];
        foreach ($this->getLogics($logicRepository) as $logic) {
            $cachedCount = LogicResult::forLogic($logic)->count();
            $results[] = [
                $logic->id,
                $logic->name,
                $this->option('percentage')
                    ? sprintf('%s/%s (%u%%)', $cachedCount, $combinations, ($cachedCount / $combinations) * 100)
                    : sprintf('%s', $cachedCount)
            ];
        }

        $this->table([
            'ID', 'Name', 'Cached'
        ], $results);

    }

    private function getLogics(LogicRepository $logicRepository): array
    {
        if ($this->argument('logic') !== null) {
            return [$logicRepository->getById($this->argument('logic'))];
        }
        return collect($logicRepository->all())->all();
    }
}

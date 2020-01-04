<?php

namespace BristolSU\Support\Filters\Commands;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Jobs\CacheFilter;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\ProgressBar;

class CacheFilters extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filters:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches all filter results for increased speed in page load';

    public function handle(FilterInstanceRepository $filterInstanceRepository,
                           UserRepository $userRepository,
                           GroupRepository $groupRepository,
                           RoleRepository $roleRepository)
    {
        $this->info('Caching filters');

        $users = collect($userRepository->all());
        $groups = collect($groupRepository->all());
        $roles = collect($roleRepository->all());

        $filterInstances = $filterInstanceRepository->all();

        $filterInstanceProgress = $this->output->createProgressBar(count($filterInstances));
        $filterInstanceProgress->start();
        foreach ($filterInstances as $filterInstance) {
            if ($filterInstance->for() === 'user') {
                $this->cacheFilter($filterInstance, $users);
            } elseif ($filterInstance->for() === 'group') {
                $this->cacheFilter($filterInstance, $groups);
            } elseif ($filterInstance->for() === 'role') {
                $this->cacheFilter($filterInstance, $roles);
            }
                $filterInstanceProgress->advance();
        }

    }

    private function cacheFilter($filterInstance, Collection $models)
    {
        foreach($models as $model) {
            dispatch(new CacheFilter($filterInstance, $model));
        }
    }

}
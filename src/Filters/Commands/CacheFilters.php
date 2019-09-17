<?php

namespace BristolSU\Support\Filters\Commands;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Jobs\CacheFilter;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Command to cache the result of all filters
 */
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

    /**
     * Cache the result of all filter instances.
     * 
     * This function will fire a job to cache the filter of all filter instances.
     * 
     * @param FilterInstanceRepository $filterInstanceRepository Filter instance repository to get the filter instances from
     * @param UserRepository $userRepository Repository to get all users from
     * @param GroupRepository $groupRepository Repository to get all groups from
     * @param RoleRepository $roleRepository Repository to get all roles from
     */
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

    /**
     * Fire a job to cache the given filter with the given models
     * 
     * @param FilterInstance $filterInstance Filter instance to cache
     * @param Collection $models Models to cache the result of.
     */
    private function cacheFilter(FilterInstance $filterInstance, Collection $models)
    {
        foreach ($models as $model) {
            dispatch(new CacheFilter($filterInstance, $model));
        }
    }

}
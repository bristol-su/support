<?php

namespace BristolSU\Support\Filters\Commands;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterRepository;
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

    public function handle(FilterRepository $filterRepository,
                           UserRepository $userRepository,
                           GroupRepository $groupRepository,
                           RoleRepository $roleRepository)
    {
        $this->info('Caching filters');

        $users = collect($userRepository->all());
        $groups = collect($groupRepository->all());
        $roles = collect($roleRepository->all());

        $filterInstances = app(FilterInstanceRepository::class)->all();

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
        print "\n";
        $modelProgress = $this->output->createProgressBar(count($models));
        $modelProgress->start();
        foreach($models as $model) {
            dispatch(new CacheFilter($filterInstance, $model));
            $modelProgress->advance();
        }
        $this->output->write("\033[1A");
    }

}
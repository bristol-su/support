<?php

namespace BristolSU\Support\Logic\DatabaseDecorator;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Console\Command;

/**
 * Command to cache the result of all filters.
 */
class CacheLogic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logic:cache {logic? : The ID of the logic to cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches all logic results for increased speed in page load';

    /**
     * Cache the result of all filter instances.
     *
     * This function will fire a job to cache the filter of all filter instances.
     *
     * @param UserRepository $userRepository Repository to get all users from
     */
    public function handle(UserRepository $userRepository) {
//        LogicResult::whereNotNull('id')->delete(); // TODO Remoev this, it deletes all logic whilst testing
        $this->info('Caching logic');

        $users = collect($userRepository->all());

        $userProgress = $this->output->createProgressBar($users->count());
        $userProgress->start();
        foreach($users as $user) {
            dispatch(new CacheLogicJob($user, $this->argument('logic')));
            $userProgress->advance();
        }
        $userProgress->finish();
    }
}

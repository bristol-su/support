<?php

namespace BristolSU\Support\Progress\Commands;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Contracts\Repository;
use BristolSU\Support\Progress\Jobs\UpdateProgress as UpdateProgressJob;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateProgress extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progress:snapshot
                                {activity? : ID of the activity to take a snapshot of. Leave blank for all activities.}
                                {--E|exporter=database : The exporter to use as defined in the config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all progress';

    public function handle()
    {
        foreach ($this->activities() as $activity) {
            dispatch($this->newJob($activity));
        }
    }

    /**
     * Get all activities
     *
     * @return Collection|Activity[]
     */
    private function activities(): Collection
    {
        $repository = app(Repository::class);
        if($this->argument('activity') !== null) {
            return collect([$repository->getById($this->argument('activity'))]);
        }
        return $repository->all();
    }

    public function newJob($activity)
    {
        return new UpdateProgressJob($activity, $this->option('exporter'));
    }
}
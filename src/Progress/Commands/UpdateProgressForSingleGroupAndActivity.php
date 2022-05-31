<?php

namespace BristolSU\Support\Progress\Commands;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Contracts\Repository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\Progress\Jobs\UpdateProgress as UpdateProgressJob;
use BristolSU\Support\Progress\Jobs\UpdateProgressForGivenActivityInstances;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateProgressForSingleGroupAndActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progress:snapshot-group
                                {activity? : ID of the activity to take a snapshot of. Leave blank for all activities.}
                                {group? : ID of the group to take a snapshot of.}
                                {--E|exporter=database : The exporter to use as defined in the config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all progress for a group';

    public function handle()
    {
        foreach ($this->activities() as $activity) {
            dispatch($this->newJob($activity));
        }
    }

    /**
     * Get all activities.
     *
     * @return Collection|Activity[]
     */
    private function activities(): Collection
    {
        $repository = app(Repository::class);
        if ($this->argument('activity') !== null) {
            return collect([$repository->getById($this->argument('activity'))]);
        }

        return $repository->all();
    }

    public function newJob($activity)
    {
        $activityInstance = app(ActivityInstanceRepository::class)->firstFor($activity->id, 'group', (int) $this->argument('group'));
        return new UpdateProgressForGivenActivityInstances([$activityInstance], $this->option('exporter'));
    }
}

<?php


namespace BristolSU\Support\Progress\Jobs;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\Snapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateProgressForGivenActivityInstances implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var ActivityInstance[]
     */
    private array|Collection $activityInstances;

    private string $driver;

    /**
     * UpdateProgressForGivenActivityInstances constructor.
     * @param ActivityInstance[] $activityInstances
     * @param string $driver
     */
    public function __construct(array|Collection $activityInstances, string $driver = 'database')
    {
        $this->activityInstances = $activityInstances;
        $this->driver = $driver;
    }

    public function handle(Snapshot $snapshot)
    {
        $progresses = [];
        foreach ($this->activityInstances as $activityInstance) {
            $progresses[] = $snapshot->ofUpdateToActivityInstance($activityInstance, $this->driver);
        }

        $filteredProgresses = array_values(array_filter($progresses));

        if ($filteredProgresses) {
            \Log::info(sprintf('Updating progress for instances, 1 progress to update for driver %s.', $this->driver));
            ProgressExport::driver($this->driver)->saveMany($filteredProgresses);
        }
    }
}

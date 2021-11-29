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
use Illuminate\Support\Facades\Log;

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
        $this->onQueue('progress');
    }

    public function handle(Snapshot $snapshot)
    {
        $progresses = [];
        foreach ($this->activityInstances as $activityInstance) {
            $progresses[] = $snapshot->ofUpdateToActivityInstance($activityInstance, $this->driver);
        }

        $progresses = array_values(array_filter($progresses));

        Log::info(sprintf('Gathered progress and exporting %u', count($progresses)));

        if ($progresses) {
            ProgressExport::driver($this->driver)->saveMany($progresses);
        }
    }
}

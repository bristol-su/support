<?php


namespace BristolSU\Support\Progress\Jobs;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\Snapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UpdateProgressForGivenActivityInstances implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels, InteractsWithQueue;

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
        $this->onQueue(sprintf('progress_%s', config('app.env')));
    }

    public function handle(Snapshot $snapshot)
    {
        $progresses = [];
        foreach ($this->activityInstances as $activityInstance) {
            $progresses[] = $snapshot->ofUpdateToActivityInstance($activityInstance, $this->driver);
        }

        $filteredProgresses = array_values(array_filter($progresses));

        if ($filteredProgresses) {
            ProgressExport::driver($this->driver)->saveMany($filteredProgresses);
        }
    }

    public function middleware()
    {
        return [new WithoutOverlapping()];
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(10);
    }
}

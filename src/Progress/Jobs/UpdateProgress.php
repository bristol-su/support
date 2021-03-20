<?php


namespace BristolSU\Support\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateProgress implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var Activity
     */
    private $activity;

    private string $driver;

    const CHUNK_SIZE = 20;

    public function __construct(Activity $activity, string $driver = 'database')
    {
        $this->activity = $activity;
        $this->driver = $driver;
    }

    public function handle(ActivityInstanceRepository $activityInstanceRepository)
    {
        $activityInstances = [];
        foreach ($activityInstanceRepository->allForActivity($this->activity->id) as $activityInstance) {
            $activityInstances[] = $activityInstance;
            if (count($activityInstances) >= static::CHUNK_SIZE) {
                UpdateProgressForGivenActivityInstances::dispatch($activityInstances, $this->driver);
                $activityInstances = [];
            }
        }

        if (count($activityInstances) > 0) {
            UpdateProgressForGivenActivityInstances::dispatch($activityInstances, $this->driver);
        }
    }
}

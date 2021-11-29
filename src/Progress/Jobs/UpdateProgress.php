<?php


namespace BristolSU\Support\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

    /**
     * @var int
     */
    const CHUNK_SIZE = 2;

    public function __construct(Activity $activity, string $driver = 'database')
    {
        $this->activity = $activity;
        $this->driver = $driver;
        $this->onQueue('progress');
    }

    public function handle(ActivityInstanceRepository $activityInstanceRepository)
    {
        foreach ($activityInstanceRepository->allForActivity($this->activity->id)->chunk(static::CHUNK_SIZE) as $activityInstances) {
            UpdateProgressForGivenActivityInstances::dispatch($activityInstances->values()->all(), $this->driver);
        }
    }
}

<?php


namespace BristolSU\Support\Progress;

use BristolSU\ControlDB\Export\RunExport;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator;
use BristolSU\Support\Progress\Contracts\ProgressUpdateContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Snapshot
{
    protected ProgressUpdateContract $ProgressUpdateRepository;

    /**
     * Snapshot constructor.
     * @param ProgressUpdateContract $ProgressUpdateRepository
     */
    public function __construct(ProgressUpdateContract $ProgressUpdateRepository)
    {
        $this->ProgressUpdateRepository = $ProgressUpdateRepository;
    }

    /**
     * Generates any progress of an Activity (All NULL values are excluded).
     *
     * @param Activity $activity
     * @param string $caller
     * @return array
     */
    public function ofUpdatesToActivity(Activity $activity, string $caller): array
    {
        $progresses = [];
        foreach (app(ActivityInstanceRepository::class)->allForActivity($activity->id) as $activityInstance) {
            $progresses[] = $this->ofUpdateToActivityInstance($activityInstance, $caller);
        }

        return array_filter($progresses);
    }

    /**
     * Returns Progress for an Activity Instance.
     *
     * @param ActivityInstance $activityInstance
     * @param string $caller
     * @return Progress|null
     */
    public function ofUpdateToActivityInstance(ActivityInstance $activityInstance, string $caller): ?Progress
    {
        // Get the Current Progress:
        $currentProgress = $this->ofActivityInstance($activityInstance);

        if ($this->ProgressUpdateRepository->hasChanged($activityInstance->id, $caller, $currentProgress)) {
            // Save Changes and return Progress
            $this->ProgressUpdateRepository->saveChanges($activityInstance->id, $caller, $currentProgress);

            return $currentProgress;
        }

        return null;
    }

    /**
     * Take a snapshot of an entire activity.
     *
     * @param Activity $activity
     *
     * @return array|Progress[]
     */
    public function ofActivity(Activity $activity): array
    {
        $progresses = [];
        foreach (app(ActivityInstanceRepository::class)->allForActivity($activity->id) as $activityInstance) {
            $progresses[] = $this->ofActivityInstance($activityInstance);
        }

        return $progresses;
    }

    /**
     * Take a snapshot of a single activity instance.
     *
     * @param ActivityInstance $activityInstance
     *
     * @return Progress
     */
    public function ofActivityInstance(ActivityInstance $activityInstance): Progress
    {
        $activity = $activityInstance->activity;
        $percentages = 0;
        $moduleCount = 0;
        $progress = Progress::create($activity->id, $activityInstance->id, Carbon::now(), true, 0);

        foreach ($activity->moduleInstances as $moduleInstance) {
            $evaluation = ModuleInstanceEvaluator::evaluateResource($activityInstance, $moduleInstance);
            $moduleInstanceProgress = ModuleInstanceProgress::create(
                $moduleInstance->id,
                $evaluation->mandatory(),
                $evaluation->complete(),
                $evaluation->percentage(),
                $evaluation->active(),
                $evaluation->visible()
            );
            if ($evaluation->mandatory()) {
                $moduleCount++;
                $percentages += $evaluation->percentage();
                if (!$evaluation->complete()) {
                    $progress->setComplete(false);
                }
            }
            $progress->pushModule($moduleInstanceProgress);
        }

        if ($moduleCount > 0) {
            $progress->setPercentage($percentages / $moduleCount);
        }

        return $progress;
    }
}

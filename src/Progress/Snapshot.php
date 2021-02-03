<?php


namespace BristolSU\Support\Progress;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator;
use Carbon\Carbon;

class Snapshot
{
    /**
     * Take a snapshot of an entire activity.
     *
     * @param \BristolSU\Support\Activity\Activity $activity
     *
     * @return array|Progress[]
     */
    public function ofActivity(\BristolSU\Support\Activity\Activity $activity): array
    {
        $progresses = [];
        foreach (app(ActivityInstanceRepository::class)->allForActivity($activity->id) as $activityInstance) {
            $progresses[] = static::ofActivityInstance($activityInstance);
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

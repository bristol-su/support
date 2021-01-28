<?php


namespace BristolSU\Support\Progress;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator;
use BristolSU\Support\Progress\ProgressUpdateRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class Snapshot
{

    private ProgressUpdateRepository $ProgressUpdateRepository;

    public function __construct()
    {
        $this->ProgressUpdateRepository = new ProgressUpdateRepository();
    }

    public function createActivityHashArray(Progress $activityProgress)
    {
        return [
            $activityProgress->getActivityInstanceId(),
            $activityProgress->getPercentage(),
            json_encode(
                array_map(fn(ModuleInstanceProgress $moduleInstanceProgress): array => [
                    'moduleInstanceId' => $moduleInstanceProgress->getModuleInstanceId(),
                    'mandatory' => $moduleInstanceProgress->isMandatory(),
                    'complete' => $moduleInstanceProgress->isComplete(),
                    'percentage' => $moduleInstanceProgress->getPercentage(),
                    'active' => $moduleInstanceProgress->isActive(),
                    'visible' => $moduleInstanceProgress->isVisible()
                ], $activityProgress->getModules())
            )
        ];
    }

    public function ofUpdatesToActivity(Activity $activity, $caller)
    {
        $progresses = [];
        foreach(app(ActivityInstanceRepository::class)->allForActivity($activity->id) as $activityInstance) {
            array_push($progresses, $this->ofUpdateToActivityInstance($activityInstance, $caller));
        }
        return array_filter($progresses);
    }

    public function ofUpdateToActivityInstance(ActivityInstance $activityInstance, $caller) : ?Progress
    {
        // Set Cache key:
        $itemKey = $caller . '_' . $activityInstance->id;

        // Get the Current Progress:
        $Progress = $this->ofActivityInstance($activityInstance);
        $progressArrayHash = $this->createActivityHashArray($Progress);

        $storedProgress = ProgressHashes::find($itemKey);

        // Check if data is missing from Cache:
        if(! $storedProgress){
            // Return all Progress to be Saved to stored:
            $this->ProgressUpdateRepository->saveHash($itemKey, $progressArrayHash);
            return $Progress;
        }

        // Check if data is different: Hash::check($this->concatActivityInstance($currentProgress), $storedProgress->hash)
        if(! $this->ProgressUpdateRepository->checkHash(
            $this->ProgressUpdateRepository->generateHash($progressArrayHash),
            $storedProgress->hash
        )) {
            // Return all Progress to be Saved to stored:
            $this->ProgressUpdateRepository->saveHash($itemKey, $progressArrayHash);

            return $Progress;
        }

        return null;
    }

    /**
     * Take a snapshot of an entire activity
     * 
     * @param Activity $activity
     * 
     * @return array|Progress[]
     */
    public function ofActivity(Activity $activity): array
    {
        $progresses = [];
        foreach(app(ActivityInstanceRepository::class)->allForActivity($activity->id) as $activityInstance) {
            $progresses[] = $this->ofActivityInstance($activityInstance);
        }
        return $progresses;
    }

    /**
     * Take a snapshot of a single activity instance
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

        foreach($activity->moduleInstances as $moduleInstance) {
            $evaluation = ModuleInstanceEvaluator::evaluateResource($activityInstance, $moduleInstance);
            $moduleInstanceProgress = ModuleInstanceProgress::create(
                $moduleInstance->id,
                $evaluation->mandatory(),
                $evaluation->complete(),
                $evaluation->percentage(),
                $evaluation->active(),
                $evaluation->visible()
            );
            if($evaluation->mandatory()) {
                $moduleCount++;
                $percentages += $evaluation->percentage();
                if(!$evaluation->complete()) {
                    $progress->setComplete(false);
                }
            }
            $progress->pushModule($moduleInstanceProgress);
        }
        
        if($moduleCount > 0) {
            $progress->setPercentage($percentages / $moduleCount);
        }
        
        return $progress;
    }

}
<?php


namespace BristolSU\Support\Progress\Handlers\Database;


use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\Progress;

class DatabaseHandler implements Handler
{

    /**
     * @param array|Progress[] $progresses
     */
    public function saveMany(array $progresses): void
    {
        foreach($progresses as $progress) {
            $this->save($progress);
        }
    }

    public function save(Progress $progress): void
    {
        $progressModel = \BristolSU\Support\Progress\Handlers\Database\Models\Progress::create([
            'activity_instance_id' => $progress->getActivityInstanceId(),
            'complete' => $progress->isComplete(),
            'percentage' => $progress->getPercentage(),
            'timestamp' => $progress->getTimestamp()
        ]);
        
        foreach($progress->getModules() as $moduleInstanceProgress) {
            ModuleInstanceProgress::create([
                'module_instance_id' => $moduleInstanceProgress->getModuleInstanceId(),
                'progress_id' => $progressModel->id,
                'mandatory' => $moduleInstanceProgress->isMandatory(),
                'complete' => $moduleInstanceProgress->isComplete(),
                'percentage' => $moduleInstanceProgress->getPercentage(),
                'active' => $moduleInstanceProgress->isActive(),
                'visible' => $moduleInstanceProgress->isVisible()
            ]);
        }
    }
}
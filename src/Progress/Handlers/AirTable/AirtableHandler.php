<?php


namespace BristolSU\Support\Progress\Handlers\AirTable;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use Carbon\Carbon;

class AirtableHandler implements Handler
{

    protected string $baseId;
    protected string $tableName;
    protected string $apiKey;

    public function __construct(string $baseId, string $tableName, string $apiKey)
    {
        $this->baseId = $baseId;
        $this->tableName = $tableName;
        $this->apiKey = $apiKey;
    }

    protected function filterModules(\Closure $filter, Progress $progress, $moduleInstances)
    {
        return collect($progress->getModules())
            ->filter($filter)->map(function (ModuleInstanceProgress $moduleInstanceProgress) use ($moduleInstances) {
                return $moduleInstances[$moduleInstanceProgress->getModuleInstanceId()];
            })->values()->toArray();
    }

    protected function parseProgress(Progress $progress)
    {
        $activityInstance = app(ActivityInstanceRepository::class)
            ->getById($progress->getActivityInstanceId());
        $moduleInstances = app(ModuleInstanceRepository::class)
            ->allThroughActivity($activityInstance->activity)
            ->reduce(function ($carry, ModuleInstance $moduleInstance) {
                $carry[$moduleInstance->id()] = $moduleInstance->name;
                return $carry;
            });

        return [
            'fields' => [
                'Participant Name' => $activityInstance->participantName(),
                'Mandatory Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return $moduleInstanceProgress->isMandatory();
                }, $progress, $moduleInstances),
                'Optional Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return !$moduleInstanceProgress->isMandatory();
                }, $progress, $moduleInstances),
                'Complete Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return $moduleInstanceProgress->isComplete();
                }, $progress, $moduleInstances),
                'Incomplete Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return !$moduleInstanceProgress->isComplete();
                }, $progress, $moduleInstances),
                'Active Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return $moduleInstanceProgress->isActive();
                }, $progress, $moduleInstances),
                'Inactive Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return !$moduleInstanceProgress->isActive();
                }, $progress, $moduleInstances),
                'Hidden Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return !$moduleInstanceProgress->isVisible();
                }, $progress, $moduleInstances),
                'Visible Modules' => $this->filterModules(function (ModuleInstanceProgress $moduleInstanceProgress) {
                    return $moduleInstanceProgress->isVisible();
                }, $progress, $moduleInstances),
                '% Complete' => $progress->getPercentage(),
                'Activity Instance ID' => $activityInstance->id,
                'Activity ID' => $progress->getActivityId(),
                'Participant ID' => $activityInstance->resource_id,
                'Snapshot Date' => $progress->getTimestamp()->format(\DateTime::ATOM)
            ]
        ];

    }

    /**
     * @param array|Progress[] $progresses
     */
    public function saveMany(array $progresses): void
    {
        $data = [];
        foreach ($progresses as $progress) {
            $data[] = $this->parseProgress($progress);
        }
        $this->createRecords($data);
    }

    public function save(Progress $progress): void
    {
        $this->saveMany([$progress]);
    }

    protected function createRecords(array $data)
    {
        $secondsToDelay = 0;

        foreach (collect($data)->chunk(10) as $fields) {
            dispatch(
                new CreateRecords(['records' => $fields->values()->toArray(), 'typecast' => true], $this->apiKey,
                    $this->baseId, $this->tableName)
            )->delay(Carbon::now()->addSeconds($secondsToDelay));
            $secondsToDelay += 2;
        }
    }
}
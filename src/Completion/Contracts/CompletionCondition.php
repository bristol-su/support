<?php

namespace BristolSU\Support\Completion\Contracts;

use BristolSU\Module\UploadFile\Models\File;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

abstract class CompletionCondition
{
    /**
     * @var string
     */
    private $moduleAlias;

    public function __construct(string $moduleAlias)
    {
        $this->moduleAlias = $moduleAlias;
    }

    public function moduleAlias()
    {
        return $this->moduleAlias;
    }

    /**
     * Return the percentage completion of the module
     * 
     * @param $settings
     * @param ActivityInstance $activityInstance
     * @param ModuleInstance $moduleInstance
     * @return int
     */
    public function percentage($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): int 
    {
        if($this->isComplete($settings, $activityInstance, $moduleInstance)) {
            return 100;
        }
        return 0;
    }
    
    abstract public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool;

    abstract public function options(): array;

    abstract public function name(): string;

    abstract public function description(): string;

    abstract public function alias(): string;
}
<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;

/**
 * Adds features for Module Instance and Activity Instance id references.
 *
 * @method static Builder forResource(int $activityInstanceId = null, int $moduleInstanceId = null)
 * @method static Builder forModuleInstance(int $moduleInstanceId = null)
 */
trait HasResource
{
    /**
     * Boot the trait.
     *
     * When a model is saved, if activity_instance_id or module_instance_id are null they will be set to the
     * id of the model resolved from the container.
     */
    public static function bootHasResource()
    {
        static::saving(function ($model) {
            if ($model->activity_instance_id === null) {
                $model->activity_instance_id = static::activityInstanceId();
            }
            if ($model->module_instance_id === null) {
                $model->module_instance_id = static::moduleInstanceId();
            }

            return $model;
        });
    }

    /**
     * Returns the module instance resolved from the container.
     *
     * @return int
     */
    public static function moduleInstanceId()
    {
        return app(ModuleInstance::class)->id;
    }

    /**
     * Returns the resolved activity instance.
     *
     * @return int
     */
    public static function activityInstanceId()
    {
        return app(ActivityInstanceResolver::class)
            ->getActivityInstance()
            ->id;
    }

    /**
     * Retrieves models for the current user.
     *
     * A scope to select only items which have the module instance and activity as are being used. Use on the participant
     * side of modules to quickly retrieve models accessible by the user.
     *
     * Make sure to create two unsignedBigInt rows on the table called 'activity_instance_id' and 'module_instance_id'
     *
     * @param Builder $query
     * @param null|mixed $activityInstanceId
     * @param null|mixed $moduleInstanceId
     * @throws \Exception
     */
    public function scopeForResource(Builder $query, $activityInstanceId = null, $moduleInstanceId = null)
    {
        $query->where('activity_instance_id', ($activityInstanceId??static::activityInstanceId()))
            ->where('module_instance_id', ($moduleInstanceId??static::moduleInstanceId()));
    }

    /**
     * Return all models for the current module instance.
     *
     * A scope for the admin side of a module, where it returns all models which are associated to the current module instance
     *
     * @param Builder $query
     * @param null $moduleInstanceId
     */
    public function scopeForModuleInstance(Builder $query, $moduleInstanceId = null)
    {
        $query->where('module_instance_id', ($moduleInstanceId??static::moduleInstanceId()));
    }
}

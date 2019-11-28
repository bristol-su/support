<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasResource
 * @package BristolSU\Support\Authentication
 */
trait HasResource
{
    
    public static function bootHasResource()
    {
        static::saving(function($model) {
            if($model->activity_instance_id === null) {
                $model->activity_instance_id = static::activityInstanceId();
            }
            if($model->module_instance_id === null) {
                $model->module_instance_id = static::moduleInstanceId();
            }
            return $model;
        });
    }

   public static function moduleInstanceId() {
        return app(ModuleInstance::class)->id;
   }

    public static function activityInstanceId()
    {
        return app(ActivityInstanceResolver::class)
            ->getActivityInstance()
            ->id;
    }

    /**
     * @param Builder $query
     * @throws \Exception
     */
    public function scopeForResource(Builder $query)
    {
        $query->where('activity_instance_id', static::activityInstanceId())
            ->where('module_instance_id', static::moduleInstanceId());
    }
    
}
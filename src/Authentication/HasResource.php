<?php

namespace BristolSU\Support\Authentication;

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
            if($model->resource_id === null || $model->resource_type === null) {
                $model->resource_id = static::resourceId();
                $model->resource_type = static::resourceType();
            }
            return $model;
        });
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function resourceType()
    {
        return app()->make(ModuleInstance::class)->activity->activity_for;
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function resourceId()
    {
        $authentication = app()->make(Authentication::class);
        $resourceType = static::resourceType();
        if ($resourceType === 'user') {
            $model = $authentication->getUser();
        } elseif ($resourceType === 'group') {
            $model = $authentication->getGroup();
        } elseif ($resourceType === 'role') {
            $model = $authentication->getRole();
            }
        if(!$model) {
            throw new \Exception(sprintf('You must be logged in as a %s', $resourceType), 403);
        }
        return $model->id;
    }

    /**
     * @param Builder $query
     * @throws \Exception
     */
    public function scopeForResource(Builder $query)
    {
        $query->where('resource_type', static::resourceType())
            ->where('resource_id', static::resourceId());
    }
    
}
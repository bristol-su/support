<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;

trait HasResource
{
    

    public static function bootHasResource()
    {
        static::saving(function($model) {
            if($model->resourceId === null || $model->resourceType === null) {
                $authentication = app()->make(Authentication::class);
                $moduleInstance = app()->make(ModuleInstance::class);

                $resourceType = $moduleInstance->activity->activity_for;
                $resourceId = null;
                if ($resourceType === 'user') {
                    $user = $authentication->getUser();
                    if(!$user) {
                        throw new \Exception('You must be logged in as a user', 403);
                    }
                    $resourceId = $user->id;
                } elseif ($resourceType === 'group') {
                    $group = $authentication->getGroup();
                    if(!$group) {
                        throw new \Exception('You must be logged in as a group', 403);
                    }
                    $resourceId = $group->id;
                }
                
                $model->resource_id = $resourceId;
                $model->resource_type = $resourceType;
            }
            return $model;
        });
    }

    public function scopeForResource(Builder $query)
    {
        [$resourceType, $resourceId] = $this->getResourceInformation();

        $query->where('resource_type', $resourceType);
        $query->where('resource_id', $resourceId);
    }
    
    private function getResourceInformation()
    {
        $authentication = app()->make(Authentication::class);
        $moduleInstance = app()->make(ModuleInstance::class);
        
        $resourceType = $moduleInstance->activity->activity_for;
        $resourceId = null;
        if ($resourceType === 'user') {
            $user = $authentication->getUser();
            if(!$user) {
                throw new \Exception('You must be logged in as a user', 403);
            }
            $resourceId = $user->id;
        } elseif ($resourceType === 'group') {
            $group = $authentication->getGroup();
            if(!$group) {
                throw new \Exception('You must be logged in as a group', 403);
            }
            $resourceId = $group->id;
        }
        return [$resourceType, $resourceId];
    }

}
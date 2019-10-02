<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;

trait HasResource
{

    public function scopeForResource(Builder $query)
    {
        $authentication = app()->make(Authentication::class);
        $moduleInstance = app()->make(ModuleInstance::class);

        $resourceType = $moduleInstance->activity->activity_for;
        $resourceId = null;
        if ($resourceType === 'user') {
            $resourceId = $authentication->getUser()->id;
        } elseif ($resourceType === 'group') {
            $resourceId = $authentication->getGroup()->id;
        }
        
        $query->where('resource_type', $resourceType);
        $query->where('resource_id', $resourceId);
    }

}
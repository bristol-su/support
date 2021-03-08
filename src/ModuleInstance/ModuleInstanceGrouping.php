<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ModuleInstanceGrouping extends Model
{
    protected $table = 'module_instance_grouping';
    
    protected $fillable = [
        'heading',
        'order'
    ];

    public function heading()
    {
        return $this->heading;
    }

    public function scopeForActivity(Builder $query, Activity $activity)
    {
        $groupingIds = collect();
        foreach (app(ModuleInstanceRepositoryContract::class)->allThroughActivity($activity) as $moduleInstance) {
            if ($moduleInstance->grouping_id === null) {
                continue;
            }
            $groupingIds->push($moduleInstance->grouping_id);
        }

        return $query->whereIn('id', $groupingIds->unique());
    }
}

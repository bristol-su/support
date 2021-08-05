<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use Database\Factories\ModuleInstanceGroupingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleInstanceGrouping extends Model
{
    use HasFactory;

    protected $table = 'module_instance_grouping';

    protected $fillable = [
        'heading'
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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ModuleInstanceGroupingFactory();
    }
}

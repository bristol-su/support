<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ModuleInstanceGrouping extends Model implements Sortable
{
    use SortableTrait, HasRevisions;

    protected $table = 'module_instance_grouping';

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'heading',
        'order',
        'activity_id'
    ];

    public function heading()
    {
        return $this->heading;
    }

    public function buildSortQuery()
    {
        return static::query()->where('activity_id', $this->activity_id);
    }

    public function scopeForActivity(Builder $query, Activity $activity)
    {
        return $query->where('activity_id', $activity->id);
    }
}

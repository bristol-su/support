<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ModuleInstanceGroupingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ModuleInstanceGrouping extends Model implements Sortable
{
    use HasFactory, SortableTrait, HasRevisions;

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

<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\FilterInstance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Logic
 * @package BristolSU\Support\Logic
 */
class Logic extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = [
        'lowest_resource'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filters()
    {
        return $this->hasMany(FilterInstance::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allTrueFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'all_true');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allFalseFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'all_false');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anyTrueFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'any_true');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anyFalseFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'any_false');
    }

    public function getLowestResourceAttribute()
    {
        /** @var Collection $filters */
        $filters = $this->filters;
        if($filters->contains('for', 'role')) {
            return 'role';
        } else if($filters->contains('for', 'group')) {
            return 'group';
        } else if($filters->contains('for', 'user')) {
            return 'user';
        }
        return 'none';
    }
}

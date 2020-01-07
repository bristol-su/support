<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\FilterInstance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Represents a logic group in the database
 */
class Logic extends Model
{
    /**
     * Fillable properties
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Additional properties
     * 
     * - Lowest Resource: The lowest form of resource required. Returns either user, group or role.
     *      If user is returned, it means the logic group will ALWAYS return false if a user, group or role is not givn
     *      If group is returned, it means the logic group will ALWAYS return false if a group or role is not given
     *      If role is returned, it means the logic group will ALWAYS return false if a role is not given
     * 
     * @var array 
     */
    protected $appends = [
        'lowest_resource'
    ];
    
    /**
     * Filter relationship
     * 
     * Returns all filters constituting the logic group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filters()
    {
        return $this->hasMany(FilterInstance::class);
    }


    /**
     * Filter relationship for filters that must all be true
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allTrueFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'all_true');
    }

    /**
     * Filter relationship for filters that must all be false
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allFalseFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'all_false');
    }

    /**
     * Filter relationship for filters of which any must be true
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anyTrueFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'any_true');
    }

    /**
     * Filter relationship for filters of which any must be false
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anyFalseFilters()
    {
        return $this->hasMany(FilterInstance::class)->where('filter_instances.logic_type', '=', 'any_false');
    }

    /**
     * Method for getting the lowest resource.
     * 
     * Resources are stacked. i.e. you either have a user, a user AND a group or a user, group AND role. This will return 
     * the lowest type of resource required to return true. If our logic group contains a role filter, in order for the 
     * logic group to return a meaningful result (i.e. not just false), a role must be given to the tester. Therefore, this
     * function will return role in that case.If, on the other hand, our logic group only contains user and group filters, 
     * passing in an additional role won't make a difference to the logic tester so we only NEED a user and a group. This
     * function will then return group
     * 
     * @return string
     */
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

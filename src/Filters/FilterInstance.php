<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FilterInstance
 * @package BristolSU\Support\Filters
 */
class FilterInstance extends Model implements FilterInstanceContract
{

    /**
     * @var array
     */
    protected $fillable = [
        'alias', 'name', 'settings'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function settings()
    {
        return $this->settings;
    }

    public function for ()
    {
        $filter = app(FilterRepositoryContract::class)->getByAlias($this->alias());
        if ($filter instanceof UserFilter) {
            return 'user';
        }
        if ($filter instanceof GroupFilter) {
            return 'group';
        }
        if ($filter instanceof RoleFilter) {
            return 'role';
        }
        throw new \Exception('Filter must extend Filter contract');
    }
    
    /**
     * @return BelongsTo
     */
    public function logic()
    {
        return $this->belongsTo(Logic::class);
    }

    public function getForAttribute()
    {
        return $this->for();
    }

    /**
     * @return mixed
     */
    public function alias()
    {
        return $this->alias;
    }


}

<?php


namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\FilterInstanceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents a filter instance.
 */
class FilterInstance extends Model implements FilterInstanceContract
{
    use HasRevisions, HasFactory, SoftDeletes;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'alias', 'name', 'settings'
    ];

    /**
     * Cast settings to an array.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * Append the 'for' attribute to the Laravel model.
     *
     * @var array
     */
    protected $appends = [
        'for'
    ];

    protected static function booted()
    {
        static::deleted(function (FilterInstance $filterInstance) {
            AudienceChanged::dispatch([$filterInstance]);
        });
        static::created(function (FilterInstance $filterInstance) {
            if ($filterInstance->logic_id) {
                AudienceChanged::dispatch([$filterInstance]);
            }
        });
        static::updated(function (FilterInstance $filterInstance) {
            if ($filterInstance->isDirty(['settings', 'logic_id', 'logic_type'])) {
                AudienceChanged::dispatch([$filterInstance]);
            }
        });
    }

    /**
     * Return the filter instance name.
     *
     * @return string Filter instance name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the filter instance settings.
     *
     * @return array
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * Return who the filter instance is for.
     *
     * @throws \Exception If the filter does not extend a user, group or role filter contract
     * @return string user, group or role
     */
    public function for()
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
     * Logic relationship.
     *
     * @return BelongsTo
     */
    public function logic()
    {
        return $this->belongsTo(Logic::class);
    }

    /**
     * Dynamically get the for attribute.
     *
     * @throws \Exception If the filter does not extend a user, group or role contract
     * @return string Get the for attribute
     */
    public function getForAttribute()
    {
        return $this->for();
    }

    /**
     * Get the filter alias.
     *
     * @return string Filter alias
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new FilterInstanceFactory();
    }
}

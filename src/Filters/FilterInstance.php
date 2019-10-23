<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\Model;

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
    public function alias()
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logic()
    {
        return $this->belongsTo(Logic::class);
    }


}

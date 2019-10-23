<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Activity
 * @package BristolSU\Support\Activity
 */
class Activity extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'activity_for',
        'for_logic',
        'admin_logic',
        'start_date',
        'end_date'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    /**
     * Activity constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::creating(function($model) {
            if($model->slug === null) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstances()
    {
        return $this->hasMany(ModuleInstance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forLogic()
    {
        return $this->belongsTo(Logic::class, 'for_logic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminLogic()
    {
        return $this->belongsTo(Logic::class, 'admin_logic');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query) {
        return $query
            ->where(['start_date' => null, 'end_date'=>null])
            ->orWhere([
                ['start_date', '<=', Carbon::now()],
                ['end_date', '>=', Carbon::now()]
            ]);
    }

}

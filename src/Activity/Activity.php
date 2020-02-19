<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Activity Model
 */
class Activity extends Model
{
    /**
     * Fillable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'activity_for',
        'for_logic',
        'admin_logic',
        'start_date',
        'end_date',
        'slug',
        'type'
    ];

    /**
     * Attributes to be casted
     * 
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    /**
     * Initialise an Activity model. 
     * 
     * Set up creating event to set the slug automatically
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::creating(function($model) {
            if ($model->slug === null) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Module Instance relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstances()
    {
        return $this->hasMany(ModuleInstance::class);
    }

    public function scopeEnabled(Builder $query)
    {
        return $query->where('enabled', true);
    }
    
    /**
     * For logic relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forLogic()
    {
        return $this->belongsTo(Logic::class, 'for_logic');
    }

    /**
     * Admin logic relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminLogic()
    {
        return $this->belongsTo(Logic::class, 'admin_logic');
    }

    /**
     * Active scope
     * 
     * Only returns activities which are either not time sensitive, or within the correct time frame
     * 
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

    /**
     * Is the activity completable?
     * 
     * Can the activity be completed? An open activity cannot, but a completable activity can be
     * 
     * @return bool
     */
    public function isCompletable(): bool {
        return $this->type === 'completable' || $this->type === 'multi-completable';
    }

    /**
     * Activity Instance relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityInstances()
    {
        return $this->hasMany(ActivityInstance::class);
    }
}

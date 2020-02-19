<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/**
 * Represents a module instance in the database
 */
class ModuleInstance extends Model implements ModuleInstanceContract
{

    /**
     * Fillable attributes 
     * 
     * @var array
     */
    protected $fillable = [
        'alias',
        'activity_id',
        'name',
        'slug',
        'description',
        'active',
        'visible',
        'mandatory',
        'completion_condition_instance_id'
    ];

    /**
     * When the model is saved, the slug will be dynamically set if not given
     * 
     * @param array $attributes Attributes for the model
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
     * Get the alias of the module
     * 
     * @return string
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * Get the ID of the module
     * 
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Activity relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Settings relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstanceSettings()
    {
        return $this->hasMany(ModuleInstanceSetting::class);
    }

    /**
     * Permissions relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstancePermissions()
    {
        return $this->hasMany(ModuleInstancePermission::class);
    }

    /**
     * Completion condition relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function completionConditionInstance()
    {
        return $this->belongsTo(CompletionConditionInstance::class);
    }

    /**
     * Active logic relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeLogic()
    {
        return $this->belongsTo(Logic::class, 'active');
    }

    /**
     * Visible logic relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visibleLogic()
    {
        return $this->belongsTo(Logic::class, 'visible');
    }

    /**
     * Mandatory logic relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mandatoryLogic()
    {
        return $this->belongsTo(Logic::class, 'mandatory');
    }

    /**
     * Action instances relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionInstances()
    {
        return $this->hasMany(ActionInstance::class);
    }

    /**
     * Services relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstanceServices()
    {
        return $this->hasMany(ModuleInstanceService::class);
    }

    /**
     * Get a setting from the module instance
     * 
     * @param string $key Key of the setting
     * @param mixed|null $default Default value if the setting is not found
     * @return mixed|null
     */
    public function setting($key, $default = null)
    {
        try {
            return $this->moduleInstanceSettings()->where('key', $key)->firstOrFail()->value;
        } catch (ModelNotFoundException $e) {
            return $default;
        }
    }

    /**
     * Return only enabled module instances
     * 
     * @param Builder $query
     * 
     * @return Builder
     */
    public function scopeEnabled(Builder $query)
    {
        return $query->where('enabled', true);
    }
}

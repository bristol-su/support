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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/**
 * Class ModuleInstance
 * @package BristolSU\Support\ModuleInstance
 */
class ModuleInstance extends Model implements ModuleInstanceContract
{

    /**
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
     * ModuleInstance constructor.
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
     * @return mixed
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstanceSettings()
    {
        return $this->hasMany(ModuleInstanceSetting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstancePermissions()
    {
        return $this->hasMany(ModuleInstancePermission::class);
    }

    public function completionConditionInstance()
    {
        return $this->belongsTo(CompletionConditionInstance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeLogic()
    {
        return $this->belongsTo(Logic::class, 'active');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visibleLogic()
    {
        return $this->belongsTo(Logic::class, 'visible');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mandatoryLogic()
    {
        return $this->belongsTo(Logic::class, 'mandatory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionInstances()
    {
        return $this->hasMany(ActionInstance::class);
    }

    public function moduleInstanceServices()
    {
        return $this->hasMany(ModuleInstanceService::class);
    }

    public function setting($key, $default = null)
    {
        try {
            return $this->moduleInstanceSettings()->where('key', $key)->firstOrFail()->value;
        } catch (ModelNotFoundException $e) {
            return $default;
        }
    }
}

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
        'module_instance_settings_id',
        'module_instance_permissions_id',
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
    public function moduleInstanceSetting()
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

    public function services()
    {
        return $this->hasMany(ModuleInstanceService::class);
    }

}

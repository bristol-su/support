<?php

namespace BristolSU\Support\ModuleInstance;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModuleInstance extends Model implements ModuleInstanceContract
{

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
        'module_instance_permissions_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::creating(function($model) {
            if($model->slug === null) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function alias()
    {
        return $this->alias;
    }

    public function id()
    {
        return $this->id;
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function moduleInstanceSettings()
    {
        return $this->belongsTo(ModuleInstanceSettings::class);
    }

    public function moduleInstancePermissions()
    {
        return $this->belongsTo(ModuleInstancePermissions::class);
    }

    public function activeLogic()
    {
        return $this->belongsTo(Logic::class, 'active');
    }

    public function visibleLogic()
    {
        return $this->belongsTo(Logic::class, 'visible');
    }

    public function mandatoryLogic()
    {
        return $this->belongsTo(Logic::class, 'mandatory');
    }

    public function actions()
    {
        return $this->hasMany(ActionInstance::class);
    }

}

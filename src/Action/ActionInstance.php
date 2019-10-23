<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionInstance
 * @package BristolSU\Support\Action
 */
class ActionInstance extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'event', 'action', 'module_instance_id'  
    ];

    /**
     * @var array
     */
    protected $appends = [
        'event_fields', 'action_fields'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionInstanceFields() {
        return $this->hasMany(ActionInstanceField::class);
    }

    /**
     * @return mixed
     */
    public function getEventFieldsAttribute()
    {
        return $this->event::getFieldMetaData();
    }

    /**
     * @return mixed
     */
    public function getActionFieldsAttribute()
    {
        return $this->action::getFieldMetaData();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }
    
}
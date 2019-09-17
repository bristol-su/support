<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * ActionInstance Model.
 * 
 * Represents an Action and Event link in the database
 */
class ActionInstance extends Model
{

    /**
     * Fillable properties
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'event', 'action', 'module_instance_id'  
    ];

    /**
     * Additional properties to compute dynamically
     * 
     * @var array
     */
    protected $appends = [
        'event_fields', 'action_fields'
    ];

    /**
     * Action Instance field relationships
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionInstanceFields() {
        return $this->hasMany(ActionInstanceField::class);
    }

    /**
     * Get the field meta data for the event
     * 
     * @return mixed
     */
    public function getEventFieldsAttribute()
    {
        return $this->event::getFieldMetaData();
    }

    /**
     * Get the field meta data for the action
     * 
     * @return mixed
     */
    public function getActionFieldsAttribute()
    {
        return $this->action::getFieldMetaData();
    }

    /**
     * Module Instance relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }
    
}
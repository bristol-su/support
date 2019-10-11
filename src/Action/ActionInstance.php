<?php

namespace BristolSU\Support\Action;

use Illuminate\Database\Eloquent\Model;

class ActionInstance extends Model
{
    
    protected $fillable = [
        'name', 'description', 'event', 'action', 'module_instance_id'  
    ];
    
    protected $appends = [
        'event_fields', 'action_fields'
    ];
    
    public function actionInstanceFields() {
        return $this->hasMany(ActionInstanceField::class);
    }

    public function getEventFieldsAttribute()
    {
        return $this->event::getFieldMetaData();
    }

    public function getActionFieldsAttribute()
    {
        return $this->action::getFieldMetaData();
    }
    
}
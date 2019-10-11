<?php

namespace BristolSU\Support\Action;

use Illuminate\Database\Eloquent\Model;

class ActionInstanceField extends Model
{
    
    protected $fillable = [
        'event_field', 'action_field', 'action_instance_id'
    ];

    public function actionInstance()
    {
        return $this->belongsTo(ActionInstance::class);
    }
}
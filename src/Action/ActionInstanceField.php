<?php

namespace BristolSU\Support\Action;

use Illuminate\Database\Eloquent\Model;

/**
 * Action Instance Field Model
 */
class ActionInstanceField extends Model
{

    /**
     * Fillable Properties
     * 
     * @var array
     */
    protected $fillable = [
        'event_field', 'action_field', 'action_instance_id'
    ];

    /**
     * The action instance relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionInstance()
    {
        return $this->belongsTo(ActionInstance::class);
    }
}
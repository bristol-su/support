<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;

/**
 * Action Instance Field Model
 */
class ActionInstanceField extends Model
{
    use HasRevisions;
    
    /**
     * Fillable Properties
     * 
     * @var array
     */
    protected $fillable = [
        'action_value', 'action_field', 'action_instance_id'
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
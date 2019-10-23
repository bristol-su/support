<?php

namespace BristolSU\Support\Action;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionInstanceField
 * @package BristolSU\Support\Action
 */
class ActionInstanceField extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'event_field', 'action_field', 'action_instance_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionInstance()
    {
        return $this->belongsTo(ActionInstance::class);
    }
}
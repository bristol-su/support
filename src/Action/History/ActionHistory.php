<?php

namespace BristolSU\Support\Action\History;

use BristolSU\Support\Action\ActionInstance;
use Illuminate\Database\Eloquent\Model;

class ActionHistory extends Model
{

    protected $table = 'action_histories';
    
    protected $fillable = [
        'action_instance_id', 'event_fields', 'settings', 'message', 'success'
    ];
    
    protected $casts = [
        'event_fields' => 'array',
        'settings' => 'array',
        'success' => 'boolean'
    ];

    public function actionInstance()
    {
        return $this->belongsTo(ActionInstance::class);
    }
    
}
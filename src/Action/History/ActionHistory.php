<?php

namespace BristolSU\Support\Action\History;

use BristolSU\Support\Action\ActionInstance;
use Database\Factories\ActionHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents the action history.
 */
class ActionHistory extends Model
{
    use HasFactory;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ActionHistoryFactory();
    }
}

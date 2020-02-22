<?php

namespace BristolSU\Support\Action;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * ActionInstance Model.
 * 
 * Represents an Action and Event link in the database
 */
class ActionInstance extends Model
{
    use HasRevisions;
    
    /**
     * Fillable properties
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'event', 'action', 'module_instance_id', 'user_id'
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
     * Initialise an Action Instance model.
     *
     * Save the ID of the current user on creation
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::creating(function($model) {
            if($model->user_id === null && ($user = app(Authentication::class)->getUser()) !== null) {
                $model->user_id = $user->id();
            }
        });
    }

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

    /**
     * Get the user who created the action instance
     *
     * @return \BristolSU\ControlDB\Contracts\Models\User
     * @throws \Exception If the user ID is null
     */
    public function user(): \BristolSU\ControlDB\Contracts\Models\User
    {
        if($this->user_id === null) {
            throw new \Exception(sprintf('Action Instance #%u is not owned by a user.', $this->id));
        }
        return app(User::class)->getById($this->user_id);
    }
}
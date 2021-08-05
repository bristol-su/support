<?php

namespace BristolSU\Support\Action;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Action\History\ActionHistory;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ActionInstanceFactory;
use FormSchema\Transformers\VFGTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ActionInstance Model.
 *
 * Represents an Action and Event link in the database
 */
class ActionInstance extends Model
{
    use HasRevisions, HasFactory;

    /**
     * Fillable properties.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'event', 'action', 'module_instance_id', 'user_id', 'should_queue'
    ];

    /**
     * Additional properties to compute dynamically.
     *
     * @var array
     */
    protected $appends = [
        'event_fields', 'action_schema'
    ];

    /**
     * Properties to automatically cast.
     *
     * @var array
     */
    protected $casts = [
        'should_queue' => 'boolean'
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
        self::creating(function ($model) {
            if ($model->user_id === null && app(Authentication::class)->hasUser()) {
                $model->user_id = app(Authentication::class)->getUser()->id();
            }
        });
    }

    /**
     * Action Instance field relationships.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionInstanceFields()
    {
        return $this->hasMany(ActionInstanceField::class);
    }

    /**
     * Get the field meta data for the event.
     *
     * @return mixed
     */
    public function getEventFieldsAttribute()
    {
        return $this->event::getFieldMetaData();
    }

    /**
     * Get the field meta data for the action.
     *
     * @return mixed
     */
    public function getActionSchemaAttribute()
    {
        return (new VFGTransformer())->transformToArray($this->action::options());
    }

    /**
     * Module Instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    /**
     * Get the user who created the action instance.
     *
     * @throws \Exception If the user ID is null
     * @return \BristolSU\ControlDB\Contracts\Models\User
     */
    public function user(): \BristolSU\ControlDB\Contracts\Models\User
    {
        if ($this->user_id === null) {
            throw new \Exception(sprintf('Action Instance #%u is not owned by a user.', $this->id));
        }

        return app(User::class)->getById($this->user_id);
    }

    public function history()
    {
        return $this->hasMany(ActionHistory::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ActionInstanceFactory();
    }

}

<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ActionInstanceFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Action Instance Field Model.
 */
class ActionInstanceField extends Model
{
    use HasRevisions, HasFactory, SoftDeletes;

    /**
     * Fillable Properties.
     *
     * @var array
     */
    protected $fillable = [
        'action_value', 'action_field', 'action_instance_id'
    ];

    public function getActionValueAttribute($value)
    {
        $json = json_decode($value, true);
        if(json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }
        return $value;
    }

    public function setActionValueAttribute($value)
    {
        if(is_array($value)) {
            $value = json_encode($value);
        }
        $this->attributes['action_value'] = $value;
    }

    /**
     * The action instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
        return new ActionInstanceFieldFactory();
    }
}

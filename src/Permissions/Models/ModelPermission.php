<?php

namespace BristolSU\Support\Permissions\Models;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a permission assigned to a model.
 *
 * @method static Builder user(?int $userId = null, ?string $ability = null, ?int $moduleInstanceId = null) Get all permissions assigned to the user with the given parameters
 * @method static Builder group(?int $groupId = null, ?string $ability = null, ?int $moduleInstanceId = null) Get all permissions assigned to the group with the given parameters
 * @method static Builder role(?int $roleId = null, ?string $ability = null, ?int $moduleInstanceId = null) Get all permissions assigned to the role with the given parameters
 * @method static Builder logic(?int $logicId = null, ?string $ability = null, ?int $moduleInstanceId = null) Get all permissions assigned to the logic group with the given parameters
 */
class ModelPermission extends Model
{

    /**
     * The table to use in the database
     * 
     * @var string
     */
    protected $table = 'model_permissions';

    /**
     * Fillable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'ability',
        'model',
        'model_id',
        'result',
        'module_instance_id'
    ];

    /**
     * Attributes to cast
     * 
     * @var array
     */
    protected $casts = [
        'result' => 'boolean'
    ];

    /**
     * The related module instance
     * 
     * If the model permission belongs to a module instance, it is a module permission.
     * TODO Create a type() method to return either global or module, and use this in support to test the type.
     * TODO Create a permission method to use for 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    /**
     * Get model permissions for a user with the given attributes.
     * 
     * @param Builder $query Query object to mutate
     * @param int|null $userId User ID to return model permissions for. Leave null to return all User IDs.
     * @param string|null $ability Ability the model permissions should have. Leave null to return all abilities.
     * @param int|null $moduleInstanceId The module instance the model permission should have. Leave null to return all module instances.
     * @return Builder
     */
    public function scopeUser(Builder $query, ?int $userId = null, ?string $ability = null, ?int $moduleInstanceId = null)
    {
        $constraints = ['model' => 'user'];
        if ($userId !== null) {
            $constraints['model_id'] = $userId;
        }
        if ($ability !== null) {
            $constraints['ability'] = $ability;
        }
        if ($moduleInstanceId !== null) {
            $constraints['module_instance_id'] = $moduleInstanceId;
        }
        return $query->where($constraints);
    }

    /**
     * Get model permissions for a logic group with the given attributes
     *
     * @param Builder $query Query object to mutate
     * @param int|null $logicId Logic ID to return model permissions for. Leave null to return all Logic IDs.
     * @param string|null $ability Ability the model permissions should have. Leave null to return all abilities.
     * @param int|null $moduleInstanceId The module instance the model permission should have. Leave null to return all module instances.
     * @return Builder
     */
    public function scopeLogic(Builder $query, ?int $logicId = null, ?string $ability = null, ?int $moduleInstanceId = null)
    {
        $constraints = ['model' => 'logic'];
        if ($logicId !== null) {
            $constraints['model_id'] = $logicId;
        }
        if ($ability !== null) {
            $constraints['ability'] = $ability;
        }
        if ($moduleInstanceId !== null) {
            $constraints['module_instance_id'] = $moduleInstanceId;
        }
        return $query->where($constraints);
    }

    /**
     * Get model permissions for a group with the given attributes.
     *
     * @param Builder $query Query object to mutate
     * @param int|null $groupId Group ID to return model permissions for. Leave null to return all Group IDs.
     * @param string|null $ability Ability the model permissions should have. Leave null to return all abilities.
     * @param int|null $moduleInstanceId The module instance the model permission should have. Leave null to return all module instances.
     * @return Builder
     */
    public function scopeGroup(Builder $query, ?int $groupId = null, ?string $ability = null, ?int $moduleInstanceId = null)
    {
        $constraints = ['model' => 'group'];
        if ($groupId !== null) {
            $constraints['model_id'] = $groupId;
        }
        if ($ability !== null) {
            $constraints['ability'] = $ability;
        }
        if ($moduleInstanceId !== null) {
            $constraints['module_instance_id'] = $moduleInstanceId;
        }
        return $query->where($constraints);
    }

    /**
     * Get model permissions for a role with the given attributes.
     *
     * @param Builder $query Query object to mutate
     * @param int|null $roleId Role ID to return model permissions for. Leave null to return all Role IDs.
     * @param string|null $ability Ability the model permissions should have. Leave null to return all abilities.
     * @param int|null $moduleInstanceId The module instance the model permission should have. Leave null to return all module instances.
     * @return Builder
     */
    public function scopeRole(Builder $query, ?int $roleId = null, ?string $ability = null, ?int $moduleInstanceId = null)
    {
        $constraints = ['model' => 'role'];
        if ($roleId !== null) {
            $constraints['model_id'] = $roleId;
        }
        if ($ability !== null) {
            $constraints['ability'] = $ability;
        }
        if ($moduleInstanceId !== null) {
            $constraints['module_instance_id'] = $moduleInstanceId;
        }
        return $query->where($constraints);
    }
}

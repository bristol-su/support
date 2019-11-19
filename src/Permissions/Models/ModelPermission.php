<?php

namespace BristolSU\Support\Permissions\Models;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelPermission
 * @package BristolSU\Support\Permissions\Models
 */
class ModelPermission extends Model
{

    /**
     * @var string
     */
    protected $table = 'model_permissions';

    /**
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
     * @var array
     */
    protected $casts = [
        'result' => 'boolean'
    ];

    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }
    
    /**
     * @param Builder $query
     * @param null $userId
     * @param null $ability
     * @return Builder
     */
    public function scopeUser(Builder $query, $userId = null, $ability = null)
    {
        $constraints = ['model' => 'user'];
        if($userId !== null) {
            $constraints['model_id'] = $userId;
        }
        if($ability !== null) {
            $constraints['ability'] = $ability;
        }
        return $query->where($constraints);
    }

    /**
     * @param Builder $query
     * @param null $logicId
     * @param null $ability
     * @return Builder
     */
    public function scopeLogic(Builder $query, $logicId = null, $ability = null)
    {
        $constraints = ['model' => 'logic'];
        if($logicId !== null) {
            $constraints['model_id'] = $logicId;
        }
        if($ability !== null) {
            $constraints['ability'] = $ability;
        }
        return $query->where($constraints);
    }

    /**
     * @param Builder $query
     * @param null $groupId
     * @param null $ability
     * @return Builder
     */
    public function scopeGroup(Builder $query, $groupId = null, $ability = null)
    {
        $constraints = ['model' => 'group'];
        if($groupId !== null) {
            $constraints['model_id'] = $groupId;
        }
        if($ability !== null) {
            $constraints['ability'] = $ability;
        }
        return $query->where($constraints);
    }

    /**
     * @param Builder $query
     * @param null $groupId
     * @param null $ability
     * @return Builder
     */
    public function scopeRole(Builder $query, $groupId = null, $ability = null)
    {
        $constraints = ['model' => 'role'];
        if($groupId !== null) {
            $constraints['model_id'] = $groupId;
        }
        if($ability !== null) {
            $constraints['ability'] = $ability;
        }
        return $query->where($constraints);
    }
}

<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\Completion\GroupCompletionCondition;
use BristolSU\Support\Completion\Contracts\Completion\RoleCompletionCondition;
use BristolSU\Support\Completion\Contracts\Completion\UserCompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionInstance as CompletionConditionInstanceContract;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompletionConditionInstance
 * @package BristolSU\Support\Completion
 */
class CompletionConditionInstance extends Model implements CompletionConditionInstanceContract
{

    /**
     * @var array
     */
    protected $fillable = [
        'alias', 'name', 'settings'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * @return mixed
     */
    public function alias()
    {
        return $this->alias;
    }

    public function moduleInstance()
    {
        return $this->hasOne(ModuleInstance::class);
    }

}

<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionConditionInstance as CompletionConditionInstanceContract;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a completion condition instance
 */
class CompletionConditionInstance extends Model implements CompletionConditionInstanceContract
{
    use HasRevisions;
    
    /**
     * Fillable properties
     * 
     * @var array
     */
    protected $fillable = [
        'alias', 'name', 'settings', 'description'
    ];

    /**
     * Castable properties
     * 
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * Get the name of the completion condition instance
     * 
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get the settings for the completion condition instance
     * 
     * @return array
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * Get the alias of the completion condition instance
     * 
     * @return string
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * Module instance relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function moduleInstance()
    {
        return $this->hasOne(ModuleInstance::class);
    }

}

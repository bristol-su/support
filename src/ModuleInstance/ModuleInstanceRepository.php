<?php


namespace BristolSU\Support\ModuleInstance;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class to interact with module instances through
 */
class ModuleInstanceRepository implements ModuleInstanceRepositoryContract
{

    /**
     * Get a module instance by ID
     * 
     * @param int $id ID of the module instance
     * @return ModuleInstanceContract Module instance with the given ID
     * 
     * @throws ModelNotFoundException If the model does not exist.
     */
    public function getById(int $id): ModuleInstanceContract
    {
        return ModuleInstance::findOrFail($id);
    }

    /**
     * Create a new module instance
     * 
     * The attributes must be of the following form:
     * [
     *      'alias' => 'alias_of_the_module',
     *      '' => '',
     * ]
     * 
     * @param array $attributes
     * @return ModuleInstanceContract
     */
    public function create(array $attributes) : ModuleInstanceContract
    {
        return ModuleInstance::create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return ModuleInstance::all();
    }

    /**
     * @inheritDoc
     */
    public function allWithAlias(string $alias = ''): Collection
    {
        return ModuleInstance::where('alias', $alias)->get();
    }

    /**
     * Get all module instances that belong to a given activity
     *
     * @param Activity $activity Activity to retrieve module instances through
     *
     * @return Collection
     */
    public function allThroughActivity(Activity $activity): Collection
    {
        return $activity->moduleInstances;
    }

    /**
     * Get all enabled module instances that belong to a given activity
     *
     * @param Activity $activity Activity to retrieve enabled module instances through
     *
     * @return Collection
     */
    public function allEnabledThroughActivity(Activity $activity): Collection
    {
        return $activity->moduleInstances()->enabled()->get();

    }

    /**
     * Update a module instance
     * 
     * Parameters should be of the form ['key' => 'newValue']. Multiple values may be updated at the same time. 
     * Available keys are alias, activity_id, name, description, slug, active, visible, mandatory, completion_condition_instance_id, enabled, user_id
     * 
     * @param int $id ID of the module instance
     * @param array $attributes Attributes to be updated
     * 
     * @return ModuleInstanceContract The updated module instance
     */
    public function update(int $id, array $attributes = []): ModuleInstanceContract
    {
        $moduleInstance = $this->getById($id);
        $moduleInstance->fill($attributes);
        $moduleInstance->save();
        return $moduleInstance;
    }

    /**
     * Delete a module instance
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        $moduleInstance = $this->getById($id);
        $moduleInstance->delete();
    }


}

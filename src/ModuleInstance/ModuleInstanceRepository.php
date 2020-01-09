<?php


namespace BristolSU\Support\ModuleInstance;


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
}

<?php


namespace BristolSU\Support\ModuleInstance;


use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Class ModuleInstanceRepository
 * @package BristolSU\Support\ModuleInstance
 */
class ModuleInstanceRepository implements ModuleInstanceRepositoryContract
{

    /**
     * @param int $id
     * @return ModuleInstanceContract
     */
    public function getById(int $id): ModuleInstanceContract
    {
        return ModuleInstance::findOrFail($id);
    }

    /**
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

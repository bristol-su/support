<?php


namespace BristolSU\Support\ModuleInstance;


use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository as ModuleInstanceRepositoryContract;

class ModuleInstanceRepository implements ModuleInstanceRepositoryContract
{

    public function getById(int $id): ModuleInstanceContract
    {
        return ModuleInstance::findOrFail($id);
    }

    public function create(array $attributes) : ModuleInstanceContract
    {
        return ModuleInstance::create($attributes);
    }

}
